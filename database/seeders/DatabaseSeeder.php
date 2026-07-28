<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\School;
use App\Models\Student;
use App\Models\CameraFeed;
use App\Models\Schedule;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\Grade;
use App\Models\BehavioralLog;
use App\Models\Announcement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ── 1. School ──
        $school = School::create([
            'name' => 'Guardian Academy',
            'slug' => 'guardian',
            'is_active' => true,
        ]);

        // ── 2. Admin ──
        User::factory()->create([
            'name' => 'Dr. James Carter',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'school_id' => $school->id,
        ]);

        // ── 3. Teachers ──
        $teacher1 = User::factory()->create([
            'name' => 'Ms. Elena Rodriguez',
            'email' => 'teacher@example.com',
            'password' => bcrypt('password'),
            'role' => 'staff',
            'school_id' => $school->id,
        ]);

        $teacher2 = User::factory()->create([
            'name' => 'Mr. David Chen',
            'email' => 'teacher2@example.com',
            'password' => bcrypt('password'),
            'role' => 'staff',
            'school_id' => $school->id,
        ]);

        // ── 4. Camera Feed ──
        $feed = CameraFeed::create([
            'school_id' => $school->id,
            'display_name' => 'Grade 10A Classroom',
            'room_id' => 'Grade 10A',
            'playback_url' => 'https://fcc3ddae59ed.us-west-2.playback.live-video.net/api/video/v1/us-west-2.893648527355.channel.DmumNckWFTqz.m3u8',
            'is_active' => true,
        ]);

        CameraFeed::create([
            'school_id' => $school->id,
            'display_name' => 'Science Lab',
            'room_id' => 'Grade 11B',
            'playback_url' => 'https://fcc3ddae59ed.us-west-2.playback.live-video.net/api/video/v1/us-west-2.893648527355.channel.DmumNckWFTqz.m3u8',
            'is_active' => true,
        ]);

        // ── 5. Schedules ──
        // Teacher 1: Mathematics and Physics for Grade 10A
        Schedule::create([
            'room_id' => 'Grade 10A',
            'teacher_id' => $teacher1->id,
            'subject_name' => 'Mathematics',
            'day_of_week' => now()->dayOfWeek,
            'start_time' => '08:00',
            'end_time' => '09:30',
            'camera_feed_id' => $feed->id,
        ]);

        Schedule::create([
            'room_id' => 'Grade 10A',
            'teacher_id' => $teacher1->id,
            'subject_name' => 'Physics',
            'day_of_week' => now()->dayOfWeek,
            'start_time' => '10:00',
            'end_time' => '11:30',
            'camera_feed_id' => $feed->id,
        ]);

        Schedule::create([
            'room_id' => 'Grade 10A',
            'teacher_id' => $teacher2->id,
            'subject_name' => 'History',
            'day_of_week' => now()->dayOfWeek,
            'start_time' => '13:00',
            'end_time' => '14:30',
        ]);

        // Teacher 2: English for Grade 11B
        Schedule::create([
            'room_id' => 'Grade 11B',
            'teacher_id' => $teacher2->id,
            'subject_name' => 'English Literature',
            'day_of_week' => now()->dayOfWeek,
            'start_time' => '08:00',
            'end_time' => '09:30',
        ]);

        Schedule::create([
            'room_id' => 'Grade 11B',
            'teacher_id' => $teacher2->id,
            'subject_name' => 'World History',
            'day_of_week' => now()->dayOfWeek,
            'start_time' => '10:00',
            'end_time' => '11:30',
        ]);

        // ── 6. Parents ──
        $parent1 = User::factory()->create([
            'name' => 'Mrs. Patricia Smith',
            'email' => 'parent@example.com',
            'password' => bcrypt('password'),
            'role' => 'parent',
            'school_id' => $school->id,
        ]);

        $parent2 = User::factory()->create([
            'name' => 'Mr. Robert Johnson',
            'email' => 'parent2@example.com',
            'password' => bcrypt('password'),
            'role' => 'parent',
            'school_id' => $school->id,
        ]);

        // ── 7. Student Users ──
        $studentUser1 = User::factory()->create([
            'name' => 'Sarah Smith',
            'email' => 'student@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
            'school_id' => $school->id,
        ]);

        $studentUser2 = User::factory()->create([
            'name' => 'Marcus Johnson',
            'email' => 'student2@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
            'school_id' => $school->id,
        ]);

        $studentUser3 = User::factory()->create([
            'name' => 'Aisha Patel',
            'email' => 'student3@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
            'school_id' => $school->id,
        ]);

        // ── 8. Student Records ──
        $student1 = Student::create([
            'user_id' => $studentUser1->id,
            'school_id' => $school->id,
            'admission_number' => 'STU001',
            'first_name' => 'Sarah',
            'last_name' => 'Smith',
            'room_id' => 'Grade 10A',
            'rfid_token' => 'RFID-001',
        ]);

        $student2 = Student::create([
            'user_id' => $studentUser2->id,
            'school_id' => $school->id,
            'admission_number' => 'STU002',
            'first_name' => 'Marcus',
            'last_name' => 'Johnson',
            'room_id' => 'Grade 10A',
            'rfid_token' => 'RFID-002',
        ]);

        $student3 = Student::create([
            'user_id' => $studentUser3->id,
            'school_id' => $school->id,
            'admission_number' => 'STU003',
            'first_name' => 'Aisha',
            'last_name' => 'Patel',
            'room_id' => 'Grade 11B',
            'rfid_token' => 'RFID-003',
        ]);

        // Link parents to students
        $parent1->children()->attach($student1->id, ['relationship' => 'mother']);
        $parent2->children()->attach($student2->id, ['relationship' => 'father']);
        $parent1->children()->attach($student3->id, ['relationship' => 'guardian']); // parent1 also guardian of student3

        // ── 9. Assignments ──
        $assign1 = Assignment::create([
            'school_id' => $school->id,
            'teacher_id' => $teacher1->id,
            'subject' => 'Mathematics',
            'title' => 'Quadratic Equations Problem Set',
            'type' => 'assignment',
            'room_id' => 'Grade 10A',
            'description' => 'Solve all problems from Chapter 5. Show all work for full credit. Focus on factoring, completing the square, and the quadratic formula.',
            'due_at' => now()->addDays(3),
            'total_points' => 100,
            'is_published' => true,
        ]);

        $assign2 = Assignment::create([
            'school_id' => $school->id,
            'teacher_id' => $teacher1->id,
            'subject' => 'Physics',
            'title' => 'Newton\'s Laws of Motion Quiz',
            'type' => 'quiz',
            'room_id' => 'Grade 10A',
            'description' => 'A quick quiz covering Newton\'s three laws of motion and their applications.',
            'due_at' => now()->addDays(1),
            'total_points' => 50,
            'time_limit_minutes' => 15,
            'is_published' => true,
            'questions' => [
                [
                    'type' => 'mcq',
                    'question' => 'What is Newton\'s First Law of Motion?',
                    'options' => [
                        'An object in motion stays in motion unless acted upon by an external force',
                        'Force equals mass times acceleration',
                        'For every action there is an equal and opposite reaction',
                        'Energy cannot be created or destroyed'
                    ],
                    'correct' => 0,
                ],
                [
                    'type' => 'mcq',
                    'question' => 'Which equation represents Newton\'s Second Law?',
                    'options' => ['E = mc²', 'F = ma', 'v = d/t', 'P = mv'],
                    'correct' => 1,
                ],
                [
                    'type' => 'mcq',
                    'question' => 'What is the SI unit of force?',
                    'options' => ['Joule', 'Watt', 'Newton', 'Pascal'],
                    'correct' => 2,
                ],
                [
                    'type' => 'mcq',
                    'question' => 'If a 10kg object accelerates at 5 m/s², what is the net force?',
                    'options' => ['2 N', '15 N', '50 N', '500 N'],
                    'correct' => 2,
                ],
                [
                    'type' => 'mcq',
                    'question' => 'Newton\'s Third Law is best demonstrated by:',
                    'options' => [
                        'A ball rolling down a hill',
                        'A rocket launching (thrust vs. exhaust)',
                        'A car braking to a stop',
                        'An apple falling from a tree'
                    ],
                    'correct' => 1,
                ],
            ],
        ]);

        $assign3 = Assignment::create([
            'school_id' => $school->id,
            'teacher_id' => $teacher2->id,
            'subject' => 'History',
            'title' => 'World War II Essay',
            'type' => 'assignment',
            'room_id' => 'Grade 10A',
            'description' => 'Write a 500-word essay on the major causes of World War II and their lasting impact on global politics.',
            'due_at' => now()->addDays(7),
            'total_points' => 100,
            'is_published' => true,
        ]);

        $assign4 = Assignment::create([
            'school_id' => $school->id,
            'teacher_id' => $teacher2->id,
            'subject' => 'English Literature',
            'title' => 'Shakespeare Sonnet Analysis',
            'type' => 'assignment',
            'room_id' => 'Grade 11B',
            'description' => 'Analyze the themes, imagery, and meter of Shakespeare\'s Sonnet 18 ("Shall I compare thee to a summer\'s day?").',
            'due_at' => now()->addDays(5),
            'total_points' => 75,
            'is_published' => true,
        ]);

        // Draft (unpublished) assignment
        Assignment::create([
            'school_id' => $school->id,
            'teacher_id' => $teacher1->id,
            'subject' => 'Mathematics',
            'title' => 'Trigonometry Mid-Term Test',
            'type' => 'test',
            'room_id' => 'Grade 10A',
            'description' => 'Comprehensive mid-term examination covering trigonometric identities, graphs, and applications.',
            'due_at' => now()->addDays(14),
            'total_points' => 200,
            'time_limit_minutes' => 60,
            'is_published' => false,
            'questions' => [
                ['type' => 'mcq', 'question' => 'What is sin(90°)?', 'options' => ['0', '1', '-1', '0.5'], 'correct' => 1],
                ['type' => 'mcq', 'question' => 'What is cos(0°)?', 'options' => ['0', '1', '-1', '0.5'], 'correct' => 1],
                ['type' => 'short_answer', 'question' => 'Prove the identity: sin²θ + cos²θ = 1', 'max_words' => 200],
            ],
        ]);

        // ── 10. Submissions ──
        // Student 2 submitted assignment 1
        Submission::create([
            'assignment_id' => $assign1->id,
            'student_id' => $student2->id,
            'content' => 'Here are my solutions for the quadratic equations problem set. I used the quadratic formula for problems 1-5 and factoring for problems 6-10.',
            'submitted_at' => now()->subHours(2),
            'max_score' => 100,
            'score' => 88,
            'feedback' => 'Excellent work on the factoring problems. Minor errors on #3 and #7. Review completing the square method.',
            'graded_by' => $teacher1->id,
            'graded_at' => now()->subHour(),
        ]);

        // Student 2 took the quiz
        Submission::create([
            'assignment_id' => $assign2->id,
            'student_id' => $student2->id,
            'answers' => [0, 1, 2, 2, 1],
            'submitted_at' => now()->subHours(5),
            'max_score' => 50,
            'score' => 50, // 5/5 correct
            'graded_by' => $teacher1->id,
            'graded_at' => now()->subHours(5),
        ]);

        // ── 11. Grades ──
        // Student 1 - Sarah
        Grade::create(['student_id' => $student1->id, 'school_id' => $school->id, 'subject' => 'Mathematics', 'term' => 'Term 1', 'score' => 92, 'max_score' => 100, 'letter_grade' => 'A-', 'teacher_id' => $teacher1->id, 'remarks' => 'Exceptional problem-solving skills.']);
        Grade::create(['student_id' => $student1->id, 'school_id' => $school->id, 'subject' => 'Physics', 'term' => 'Term 1', 'score' => 88, 'max_score' => 100, 'letter_grade' => 'B+', 'teacher_id' => $teacher1->id, 'remarks' => 'Great lab work. Needs improvement on theory.']);
        Grade::create(['student_id' => $student1->id, 'school_id' => $school->id, 'subject' => 'History', 'term' => 'Term 1', 'score' => 95, 'max_score' => 100, 'letter_grade' => 'A', 'teacher_id' => $teacher2->id, 'remarks' => 'Outstanding essays and critical thinking.']);
        Grade::create(['student_id' => $student1->id, 'school_id' => $school->id, 'subject' => 'Mathematics', 'term' => 'Term 2', 'score' => 96, 'max_score' => 100, 'letter_grade' => 'A', 'teacher_id' => $teacher1->id, 'remarks' => 'Significant improvement!']);

        // Student 2 - Marcus
        Grade::create(['student_id' => $student2->id, 'school_id' => $school->id, 'subject' => 'Mathematics', 'term' => 'Term 1', 'score' => 78, 'max_score' => 100, 'letter_grade' => 'C+', 'teacher_id' => $teacher1->id, 'remarks' => 'Needs more practice with algebraic manipulation.']);
        Grade::create(['student_id' => $student2->id, 'school_id' => $school->id, 'subject' => 'Physics', 'term' => 'Term 1', 'score' => 85, 'max_score' => 100, 'letter_grade' => 'B', 'teacher_id' => $teacher1->id, 'remarks' => 'Solid understanding of core concepts.']);
        Grade::create(['student_id' => $student2->id, 'school_id' => $school->id, 'subject' => 'History', 'term' => 'Term 1', 'score' => 91, 'max_score' => 100, 'letter_grade' => 'A-', 'teacher_id' => $teacher2->id, 'remarks' => 'Excellent research skills.']);

        // Student 3 - Aisha
        Grade::create(['student_id' => $student3->id, 'school_id' => $school->id, 'subject' => 'English Literature', 'term' => 'Term 1', 'score' => 97, 'max_score' => 100, 'letter_grade' => 'A+', 'teacher_id' => $teacher2->id, 'remarks' => 'Exceptional literary analysis.']);
        Grade::create(['student_id' => $student3->id, 'school_id' => $school->id, 'subject' => 'World History', 'term' => 'Term 1', 'score' => 89, 'max_score' => 100, 'letter_grade' => 'B+', 'teacher_id' => $teacher2->id, 'remarks' => 'Great comparative analysis.']);

        // ── 12. Behavioral Logs ──
        BehavioralLog::create(['student_id' => $student1->id, 'teacher_id' => $teacher1->id, 'category' => 'Academic Excellence', 'type' => 'kudos', 'points' => 10, 'description' => 'Perfect score on pop quiz.']);
        BehavioralLog::create(['student_id' => $student1->id, 'teacher_id' => $teacher2->id, 'category' => 'Leadership', 'type' => 'kudos', 'points' => 15, 'description' => 'Led group project discussion effectively.']);
        BehavioralLog::create(['student_id' => $student2->id, 'teacher_id' => $teacher1->id, 'category' => 'Participation', 'type' => 'kudos', 'points' => 5, 'description' => 'Active participation in class discussion.']);
        BehavioralLog::create(['student_id' => $student2->id, 'teacher_id' => $teacher1->id, 'category' => 'Tardiness', 'type' => 'incident', 'points' => -5, 'description' => 'Late to class by 10 minutes.']);
        BehavioralLog::create(['student_id' => $student3->id, 'teacher_id' => $teacher2->id, 'category' => 'Creative Thinking', 'type' => 'kudos', 'points' => 20, 'description' => 'Outstanding creative writing submission.']);
        BehavioralLog::create(['student_id' => $student1->id, 'teacher_id' => $teacher1->id, 'category' => 'Homework', 'type' => 'incident', 'points' => -3, 'description' => 'Missing homework assignment.']);

        // ── 13. Announcements ──
        Announcement::create([
            'school_id' => $school->id,
            'user_id' => $teacher1->id,
            'title' => 'Physics Lab Safety Reminder',
            'content' => 'All students must wear safety goggles and closed-toe shoes during lab sessions starting next week. Lab coats will be provided.',
            'target_role' => 'all',
        ]);

        Announcement::create([
            'school_id' => $school->id,
            'user_id' => $teacher2->id,
            'title' => 'Parent-Teacher Conference Scheduled',
            'content' => 'Parent-teacher conferences will be held on April 25th from 3:00 PM to 7:00 PM. Please sign up for a time slot via the school portal.',
            'target_role' => 'parent',
        ]);

        Announcement::create([
            'school_id' => $school->id,
            'user_id' => $teacher1->id,
            'title' => 'Mathematics Competition Registration Open',
            'content' => 'The annual inter-school mathematics competition is accepting registrations. Interested students should see Ms. Rodriguez by April 20th.',
            'target_role' => 'student',
        ]);
    }
}
