<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use App\User;
use Illuminate\Http\Request;

class LanguageTestController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getLanguageTestAvailable() {
        $langs = DB::select('select * from available_lang_test where is_active = 1;');
        $langs = collect($langs)->toArray();
        return $langs;
    }

    public function getLanguageTest()
    {
        $langs = $this->getLanguageTestAvailable();
        $user = User::findOrFail(Auth::user()->id);
        $logs_test = DB::select('select lang, score, match_diff, create_at from log_lang_test where id_user = :id', ['id' => Auth::user()->id]);
        $logs_test = collect($logs_test)->toArray();
        $lang_test = DB::table('lang_test')->where('is_active', 1)->inRandomOrder()->first();
        return view('user.language_test')->with('user', $user)
                                         ->with('langs', $langs)
                                         ->with('lang_test', $lang_test)
                                         ->with('logs_test', $logs_test);
    }

    public function getParagraph(Request $request) {
        $lang = $request->input('lang');
        $lang_test = DB::table('lang_test')->where('is_active', 1)->where('lang', $lang)->inRandomOrder()->first();
        $data = [];
        $data[0] = $lang_test->id;
        $data[1] = $lang_test->paragraph;
        return $data;
    }

    public function qualifyLanguageTest(Request $request)
    {
        try {
            $lang = $request->input('lang');
            $url = $request->input('url');
            $score = $request->input('score');
            $matchdiff = $request->input('match');
            $id_paragraph = $request->input('id_paragraph');

            $score = $score * 100;
            $score = round($score,0);
            $user = User::findOrFail(Auth::user()->id);
            $oldUrl = "";

            // $existe = DB::select('select id, url from log_lang_test where id_user = :user and lang = :lang', ['user' => $user->id, 'lang' =>$lang ]);
            $existe = DB::select('select id from log_lang_test where id_user = :user and lang = :lang', ['user' => $user->id, 'lang' =>$lang ]);
            $existe = collect($existe)->toArray();

            if (count($existe) > 0) {
                // $oldUrl = $existe[0]->url;
                // DB::update('update log_lang_test set url = :url, score = :score, id_paragraph = :idp where id = :id', ['url' => $url, 'score' => $score, 'id' => $existe[0]->id, 'idp' => $id_paragraph]);
                DB::update('update log_lang_test set is_active = 0 where id_user = :id;' , ['id'=>$user->id]);
            }
            // else {
                DB::insert('insert into log_lang_test (id_user, lang, url, score, id_paragraph, is_active, match_diff) values(:user, :lang, :url, :score, :idp, 1, :matchdiff);', ['user'=>$user->id, 'lang'=>$lang, 'url'=>$url, 'score'=>$score, 'idp'=>$id_paragraph, 'matchdiff' => $matchdiff]);
            // }

            $returnHTML = view('user.forms.language_test.language_test_thanks')->with('qualification', $score)->with('diff', $matchdiff)->render();
            // return response()->json(array('success' => true, 'status' => 200, 'html' => $returnHTML, 'url'=>$oldUrl), 200);
            return response()->json(array('success' => true, 'status' => 200, 'html' => $returnHTML, 'url'=>$url), 200);
        } catch (ModelNotFoundException $e) {
            return 'notok';
        }
    }
}
