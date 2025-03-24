namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;

class ExamController extends Controller
{
    public function index() {
        $questions = Question::all();
        return view('exam', compact('questions'));
    }

    public function submit(Request $request) {
        $score = 0;
        $answers = $request->input('answers', []);
        
        foreach ($answers as $questionId => $selectedOption) {
            $question = Question::find($questionId);
            if ($question->correct_answer == $selectedOption) {
                $score++;
            }
        }

        return view('result', compact('score'));
    }
}
