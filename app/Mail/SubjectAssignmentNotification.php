<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\TeacherAssignment;

class SubjectAssignmentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $assignment;
    public $teacher;
    public $subject;
    public $registrar;

    /**
     * Create a new message instance.
     */
    public function __construct(TeacherAssignment $assignment)
    {
        $this->assignment = $assignment;
        $this->teacher = $assignment->teacher;
        $this->subject = $assignment->subject;
        $this->registrar = $assignment->assignedBy;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Subject Assignment - ' . $this->subject->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.subject-assignment-notification',
            with: [
                'teacherName' => $this->teacher->name,
                'subjectName' => $this->subject->name,
                'subjectCode' => $this->subject->code,
                'gradeLevel' => $this->subject->grade_level,
                'track' => $this->subject->track,
                'strand' => $this->subject->strand,
                'schoolYear' => $this->assignment->school_year,
                'gradingPeriod' => $this->assignment->grading_period,
                'schedule' => $this->assignment->schedule,
                'assignmentDate' => $this->assignment->assignment_date,
                'registrarName' => $this->registrar->full_name ?? $this->registrar->name,
                'notes' => $this->assignment->notes,
                'loginUrl' => route('teacher.login'),
                'dashboardUrl' => route('teacher.dashboard'),
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
