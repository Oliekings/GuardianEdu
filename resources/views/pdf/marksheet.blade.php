<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Marksheet - {{ $student->user->name }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #1a1a1a; margin: 0; padding: 40px; background: #fff; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 20px; margin-bottom: 30px; }
        .school-name { font-size: 28px; font-weight: 900; text-transform: uppercase; margin: 0; }
        .school-info { font-size: 10px; color: #666; text-transform: uppercase; letter-spacing: 1px; margin-top: 5px; }
        .document-title { margin-top: 20px; font-size: 18px; font-weight: bold; text-decoration: underline; }
        
        .student-box { border: 1px solid #ddd; padding: 20px; margin-bottom: 30px; display: flex; flex-wrap: wrap; }
        .info-group { width: 50%; margin-bottom: 10px; font-size: 12px; }
        .label { font-weight: bold; color: #555; text-transform: uppercase; font-size: 10px; }
        .value { font-weight: 900; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { background: #f8f8f8; border: 1px solid #ddd; padding: 12px; font-size: 10px; text-transform: uppercase; text-align: left; }
        td { border: 1px solid #ddd; padding: 12px; font-size: 12px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }

        .summary-box { float: right; width: 300px; border: 2px solid #000; padding: 15px; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 5px; font-size: 13px; }
        .grand-total { font-weight: 900; border-top: 1px solid #ddd; padding-top: 5px; margin-top: 5px; font-size: 16px; }

        .footer { position: fixed; bottom: 40px; left: 40px; right: 40px; }
        .signature-group { display: flex; justify-content: space-between; margin-top: 60px; }
        .signature-line { border-top: 1px solid #000; width: 200px; text-align: center; font-size: 10px; padding-top: 5px; font-weight: bold; }
        
        .grade-ribbon { background: #000; color: #fff; display: inline-block; padding: 10px 20px; font-weight: 900; font-size: 20px; text-transform: uppercase; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="school-name">{{ $school->name }}</h1>
        <div class="school-info">Secondary Education Council | Official Academic Transcript</div>
        <div class="document-title">STATEMENT OF MARKS</div>
        <div class="grade-ribbon">GRADE: {{ $grade }}</div>
    </div>

    <div class="student-box">
        <div class="info-group">
            <div class="label">Candidate Name</div>
            <div class="value">{{ $student->user->name }}</div>
        </div>
        <div class="info-group">
            <div class="label">Admission ID / UID</div>
            <div class="value">#{{ $student->admission_id }}</div>
        </div>
        <div class="info-group">
            <div class="label">Examination Series</div>
            <div class="value">{{ $exam->name }} ({{ $exam->session }})</div>
        </div>
        <div class="info-group">
            <div class="label">Term / Classification</div>
            <div class="value">{{ $exam->term }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Subject Description</th>
                <th class="text-center">Max Marks</th>
                <th class="text-center">Passing</th>
                <th class="text-right">Marks Obtained</th>
            </tr>
        </thead>
        <tbody>
            @foreach($exam->schedules as $sch)
            <tr>
                <td>{{ $sch->subject_name }}</td>
                <td class="text-center">{{ $sch->max_marks }}</td>
                <td class="text-center">{{ $sch->passing_marks }}</td>
                <td class="text-right"><strong>{{ $sch->marks->first()?->marks_obtained ?? '--' }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="overflow: hidden;">
        <div class="summary-box">
            <div class="summary-row">
                <span>Aggregate Points:</span>
                <span>{{ $totalObtained }} / {{ $totalMax }}</span>
            </div>
            <div class="summary-row grand-total">
                <span>Total Percentage:</span>
                <span>{{ $percentage }}%</span>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="signature-group">
            <div class="signature-line">Class Teacher Signature</div>
            <div class="signature-line">Principal / Controller</div>
            <div class="signature-line">Seal of Institution</div>
        </div>
        <div style="text-align: center; font-size: 8px; color: #999; margin-top: 40px;">
            This document is computer-generated and verified by the GuardianEdu Academic Protocol.
        </div>
    </div>
</body>
</html>
