<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Page;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

use function PHPSTORM_META\map;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except([
            'login',
            'loginAction',
            'register',
            'registerAction'
        ]);
    }

    public function login (Request $request)
    {
        return view('admin.login', [
            'errors' => $request->session()->get('errors'),
        ]);
    }

    public function loginAction (Request $request)
    {
        $data = $request->only([
            'email',
            'password'
        ]);

        if(Auth::attempt($data)){
            return redirect()->route('admin.index');
        } else {
            $request->session()->flash('errors', ['login' => 'E-mail e/ou senha não conferem']);

            return redirect()->route('login')->withInput([
                'email' => $data['email']
            ]);
        };
    }

    public function register (Request $request)
    {
        return view('admin.register', [
            'errors' => $request->session()->get('errors'),
        ]);
    }

    public function registerAction (Request $request)
    {
        $data = $request->only([
            'email',
            'password',
            'password_confirmation'
        ]);

        $validator = Validator::make($data, [
            'email' =>  'required|string|email|unique:users|max:50',
            'password' => 'required|string|confirmed|min:8'
        ]);

        if($validator->fails()){
            $request->session()->flash('errors', $validator->errors()->all());

            return redirect()->route('admin.register')->withInput([
                'email' => $data['email']
            ]);
        } else {
            $email = $data['email'];
            $hash = password_hash($data['password'], PASSWORD_DEFAULT);

            $newUser = new User();

            do {
                $userPublicId = Str::uuid()->toString();
            } while(User::where('public_id', $userPublicId)->count() > 0);

            $newUser->public_id = $userPublicId;
            $newUser->email = $email;
            $newUser->password = $hash;
            $newUser->save();

            Auth::login($newUser);

            return redirect()->route('admin.index');
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('admin.index');
    }

    public function index ()
    {
        $user = Auth::user();

        $pages = Page::where('id_user', $user->public_id)->get();

        echo view('admin.index', [
            'pages' => $pages
        ]);
    }

    public function pageLinks (string $slug)
    {
        $user = Auth::user();

        $page = Page::where('slug', $slug)->where('id_user', $user->public_id)
            ->first();

        if($page){
            $links = Link::where('id_page', $page->public_id)->orderBy('order', 'ASC')
                ->get();

            return view('admin.page_links', [
                'menu' => 'links',
                'page' => $page,
                'links' => $links,
            ]);
        } else {
            return redirect()->route('admin.index');
        }
    }

    public function linkOrderUpdate(string $linkid, int $pos)
    {
        $user = Auth::user();

        $link = Link::where('public_id', $linkid)->first();

        $myPages = Page::where('id_user', $user->public_id)->pluck('public_id')
            ->toArray();

        if(in_array($link->id_page, $myPages)){
            if($link->order > $pos) {
                $afterLinks = Link::where('id_page', $link->id_page)
                    ->where('order', '>=', $pos)->get();

                foreach($afterLinks as $afterLink){
                    $afterLink->order++;
                    $afterLink->save();
                };
            } else if ($link->order < $pos) {
                $beforeLinks = Link::where('id_page', $link->id_page)
                    ->where('order', '<=', $pos)->get();

                foreach($beforeLinks as $beforeLink){
                    $beforeLink->order--;
                    $beforeLink->save();
                };
            };

            $link->order = $pos;
            $link->save();

            $allLinks = Link::where('id_page', $link->id_page)
                ->orderBy('order', 'ASC')->get();

            foreach($allLinks as $key => $item){
                $item->order = $key;
                $item->save();
            };
        };
    }

    public function newLink (string $slug)
    {
        $user = Auth::user();

        $page = Page::where('id_user', $user->public_id)->where('slug', $slug)
            ->first();

        if($page){
            return view('admin.page_editlink', [
                'menu' => 'links',
                'page' => $page
            ]);
        } else {
            return redirect()->route('admin.index');
        };
    }

    public function newLinkAction (Request $request, string $slug)
    {
        $user = Auth::user();

        $page = Page::where('id_user', $user->public_id)->where('slug', $slug)
            ->first();

        if($page){
            $data = $request->only([
                'status',
                'title',
                'href',
                'op_bg_color',
                'op_text_color',
                'op_border_type',
            ]);

            $validator = Validator::make($data, [
                'status' => ['required', 'boolean'],
                'title' => ['required', 'min:2', 'max:100'],
                'href' => ['required', 'url'],
                'op_bg_color' => ['required', 'regex:/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/i'],
                'op_text_color' => ['required', 'regex:/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/i'],
                'op_border_type' => ['required', Rule::in(['square', 'rounded'])],
            ]);

            if($validator->fails()){
                $request->session()->flash('errors', $validator->errors()->all());

                return redirect()->route('admin.newlink', $slug)->withInput($data);
            } else {
                $status = $data['status'];
                $title = $data['title'];
                $href = $data['href'];
                $op_bg_color = $data['op_bg_color'];
                $op_text_color = $data['op_text_color'];
                $op_border_type = $data['op_border_type'];

                $totalLinks = Link::where('id_page', $page->public_id)->count();

                do {
                    $linkPublicId = Str::uuid()->toString();
                } while(Link::where('public_id', $linkPublicId)->count() > 0);

                $newLink = new Link();
                $newLink->public_id = $linkPublicId;
                $newLink->id_page = $page->public_id;
                $newLink->status = $status;
                $newLink->order = $totalLinks;
                $newLink->title = $title;
                $newLink->href = $href;
                $newLink->op_bg_color = $op_bg_color;
                $newLink->op_text_color = $op_text_color;
                $newLink->op_border_type = $op_border_type;
                $newLink->save();

                return redirect()->route('admin.links', $slug);
            }
        } else {
            return redirect()->route('admin.index');
        }
    }

    public function editLink(string $slug, string $linkId)
    {
        $user = Auth::user();

        $page = Page::where('id_user', $user->public_id)->where('slug', $slug)
            ->first();

        if($page){
            $link = Link::where('public_id', $linkId)
                ->where('id_page', $page->public_id)->first();

            if($link) {
                return view('admin.page_editlink', [
                    'menu' => 'links',
                    'page' => $page,
                    'link' => $link,
                ]);
            }
        };

        return redirect()->route('admin.index');
    }

    public function editLinkAction(Request $request, string $slug, string $linkId){
        $user = Auth::user();

        $page = Page::where('id_user', $user->public_id)->where('slug', $slug)
            ->first();

        if($page){
            $link = Link::where('public_id', $linkId)
                ->where('id_page', $page->public_id)->first();

            if($link) {
                $data = $request->only([
                    'status',
                    'title',
                    'href',
                    'op_bg_color',
                    'op_text_color',
                    'op_border_type',
                ]);

                $validator = Validator::make($data, [
                    'status' => ['required', 'boolean'],
                    'title' => ['required', 'min:2', 'max:100'],
                    'href' => ['required', 'url'],
                    'op_bg_color' => ['required', 'regex:/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/i'],
                    'op_text_color' => ['required', 'regex:/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/i'],
                    'op_border_type' => ['required', Rule::in(['square', 'rounded'])],
                ]);

                if($validator->fails()){
                    $request->session()->flash('errors', $validator->errors()->all());

                    return redirect()->route('admin.editlink', [
                        'menu' => 'links',
                        'page' => $page,
                        'link' => $link,
                    ])->withInput($data);
                } else {
                    $status = $data['status'];
                    $title = $data['title'];
                    $href = $data['href'];
                    $op_bg_color = $data['op_bg_color'];
                    $op_text_color = $data['op_text_color'];
                    $op_border_type = $data['op_border_type'];

                    $link->id_page = $page->public_id;
                    $link->status = $status;
                    $link->title = $title;
                    $link->href = $href;
                    $link->op_bg_color = $op_bg_color;
                    $link->op_text_color = $op_text_color;
                    $link->op_border_type = $op_border_type;
                    $link->save();

                    return redirect()->route('admin.links', $slug);
                }
            };
        };

        return redirect()->route('admin.index');
    }

    public function dellink (string $slug, string $linkId)
    {
        $user = Auth::user();

        $page = Page::where('id_user', $user->public_id)->where('slug', $slug)
            ->first();

        if($page){
            $link = Link::where('public_id', $linkId)
                ->where('id_page', $page->public_id)->first();

            if($link) {
                $link->delete();

                $allLinks = Link::where('id_page', $page->public_id)
                    ->orderBy('order', 'ASC')->get();

                foreach($allLinks as $key => $item){
                    $item->order = $key;
                    $item->save();
                };

                return redirect()->route('admin.links', $slug);
            };
        };

        return redirect()->route('admin.index');
    }

    public function pageDesign (string $slug)
    {
        return view('admin.page_design', [
            'menu' => 'design'
        ]);
    }

    public function pageStats (string $slug)
    {
        return view('admin.page_stats', [
            'menu' => 'stats'
        ]);
    }
}
