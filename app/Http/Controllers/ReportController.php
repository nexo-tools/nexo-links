<?php

namespace App\Http\Controllers;

use App\Mail\PageReported;
use App\Models\Link;
use App\Models\Page;
use App\Support\VisitorHash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function create(Page $page): View
    {
        return view('report', [
            'page' => $page,
            'links' => $page->links->filter(fn (Link $link): bool => $link->isPublished())->values(),
            'reasons' => config('nexo.report_reasons'),
        ]);
    }

    public function store(Request $request, Page $page): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => ['required', Rule::in(array_keys(config('nexo.report_reasons')))],
            'link_id' => ['nullable', Rule::exists('links', 'id')->where('page_id', $page->id)],
            'details' => ['nullable', 'string', 'max:500'],
        ]);

        $visitorHash = VisitorHash::make($request);

        $alreadyReported = $page->reports()
            ->where('visitor_hash', $visitorHash)
            ->where('created_at', '>=', now()->startOfDay())
            ->exists();

        if ($alreadyReported) {
            throw ValidationException::withMessages([
                'reason' => __('You already reported this page today.'),
            ]);
        }

        $report = $page->reports()->create([
            ...$validated,
            'visitor_hash' => $visitorHash,
        ]);

        // The owner hears it from us, not from an empty profile: a report used
        // to sit in the database until they happened to open /reports.
        Mail::to($page->user->email)
            ->locale(config('app.locale'))
            ->queue(new PageReported($page, (string) $report->reason));

        return redirect()
            ->route('report.create', $page->username)
            ->with('status', 'report-sent');
    }
}
