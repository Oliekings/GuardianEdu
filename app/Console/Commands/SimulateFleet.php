<?php

namespace App\Console\Commands;

use App\Events\BusLocationUpdated;
use App\Models\BusFleet;
use App\Models\School;
use Illuminate\Console\Command;

class SimulateFleet extends Command
{
    protected $signature = 'simulate:fleet {--count=3}';
    protected $description = 'Simulate real-time bus movement for UI testing';

    public function handle()
    {
        $school = School::first();
        if (!$school) {
            $this->error('No school found. Please seed the database first.');
            return;
        }

        $buses = BusFleet::count() > 0 ? BusFleet::all() : collect();

        if ($buses->isEmpty()) {
            $this->info('Creating virtual fleet...');
            for ($i = 1; $i <= $this->option('count'); $i++) {
                $buses->push(BusFleet::create([
                    'school_id' => $school->id,
                    'vehicle_number' => "BUS-" . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'driver_name' => "Simulator Driver " . $i,
                    'current_lat' => 6.5244 + (rand(-10, 10) / 500),
                    'current_lng' => 3.3792 + (rand(-10, 10) / 500),
                    'status' => 'en_route',
                ]));
            }
        }

        $this->info('Starting Fleet Simulation (Ctrl+C to stop)...');

        while (true) {
            foreach ($buses as $bus) {
                // Mock movement: small random deltas
                $bus->current_lat += (rand(-1, 1) / 10000);
                $bus->current_lng += (rand(-1, 1) / 10000);
                $bus->heading = rand(0, 360);
                $bus->save();

                // Broadcast
                BusLocationUpdated::dispatch($bus);
                
                $this->line("Bus {$bus->vehicle_number} moved to {$bus->current_lat}, {$bus->current_lng}");
            }
            
            sleep(2);
        }
    }
}
