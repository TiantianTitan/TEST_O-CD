<?php


namespace App\Http\Controllers;

use App\Models\Modification;
use App\Models\Person;
use App\Models\Vote;
use App\Models\Relationship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ModificationController extends Controller
{
    public function index()
    {
        $modifications = Modification::with('votes')->get();
        return view('modification', compact('modifications'));
    }

    public function vote(Request $request, $modification_id)
    {
        $request->validate([
            'vote' => 'required|in:approve,reject',
        ]);

        $modification = Modification::findOrFail($modification_id);

        // Check if the user has already voted
        if (Vote::where('modification_id', $modification_id)->where('user_id', auth()->id())->exists()) {
            return redirect()->back()->with('error', 'You have already voted on this proposal.');
        }

        // Create the vote record
        Vote::create([
            'modification_id' => $modification_id,
            'user_id' => auth()->id(),
            'vote' => $request->vote,
        ]);

        // Check if the proposal should be approved or rejected
        $this->checkVotes($modification);

        return redirect()->back()->with('success', 'Your vote has been recorded!');
    }

    protected function checkVotes(Modification $modification)
    {
        $voteCounts = DB::table('votes')
            ->select(DB::raw("SUM(vote = 'approve') as approvals, SUM(vote = 'reject') as rejections"))
            ->where('modification_id', $modification->id)
            ->first();

        $approvals = $voteCounts->approvals;
        $rejections = $voteCounts->rejections;

        if ($approvals >= 3) {
            $modification->update(['status' => 'approved']);
            $this->applyModification($modification);
            Log::info("Modification {$modification->id} approved by community.");
        } elseif ($rejections >= 3) {
            $modification->update(['status' => 'rejected']);
            Log::info("Modification {$modification->id} rejected by community.");
        }
    }

    protected function applyModification(Modification $modification)
    {
        $content = json_decode($modification->content, true);

        if ($modification->type == 'update_person') {
            Person::find($modification->target_id)->update($content);
        } elseif ($modification->type == 'add_relationship') {
            Relationship::create($content);
        }
    }
}
