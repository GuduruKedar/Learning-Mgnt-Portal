<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class StudentManualController extends Controller
{
    /**
     * Display the Student Portal User Manual web viewer with Print / Save as PDF capability.
     */
    public function show()
    {
        $filePath = base_path('STUDENT_USER_MANUAL.md');
        if (!File::exists($filePath)) {
            abort(404, 'Student manual documentation file not found.');
        }

        $markdownContent = File::get($filePath);
        $htmlContent = Str::markdown($markdownContent);

        return view('docs.student_manual', compact('htmlContent'));
    }

    /**
     * Download the official compiled PDF document (.pdf).
     */
    public function downloadPdf()
    {
        $pdfPath = public_path('docs/LMS_Student_Portal_User_Manual.pdf');
        if (!File::exists($pdfPath)) {
            $pdfPath = base_path('LMS_Student_Portal_User_Manual.pdf');
        }

        if (!File::exists($pdfPath)) {
            abort(404, 'PDF manual not found. Please contact system administrator.');
        }

        return response()->download(
            $pdfPath,
            'LMS_Student_Portal_User_Manual.pdf',
            ['Content-Type' => 'application/pdf']
        );
    }

    /**
     * Download the raw Markdown file (.md).
     */
    public function downloadMarkdown()
    {
        $filePath = base_path('STUDENT_USER_MANUAL.md');
        if (!File::exists($filePath)) {
            abort(404, 'Student manual documentation file not found.');
        }

        return response()->download(
            $filePath,
            'LMS_Student_Portal_User_Manual.md',
            ['Content-Type' => 'text/markdown; charset=UTF-8']
        );
    }

    /**
     * Download standalone printable HTML file (.html).
     */
    public function downloadHtml()
    {
        $filePath = base_path('STUDENT_USER_MANUAL.md');
        if (!File::exists($filePath)) {
            abort(404, 'Student manual documentation file not found.');
        }

        $markdownContent = File::get($filePath);
        $htmlContent = Str::markdown($markdownContent);
        $renderedHtml = view('docs.student_manual', compact('htmlContent'))->render();

        return response()->streamDownload(function () use ($renderedHtml) {
            echo $renderedHtml;
        }, 'LMS_Student_Portal_User_Manual.html', [
            'Content-Type' => 'text/html; charset=UTF-8',
        ]);
    }
}

