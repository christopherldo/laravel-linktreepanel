<?php

namespace App\Http\Controllers;

use App\Models\Click;
use App\Models\Link;
use App\Models\Page;
use App\Models\User;
use App\Models\View;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Intervention\Image\ImageManager;

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

    public function login(Request $request)
    {
        return view('admin.login', [
            'errors' => $request->session()->get('errors'),
        ]);
    }

    public function loginAction(Request $request)
    {
        $data = $request->only([
            'email',
            'password'
        ]);

        if (Auth::attempt($data)) {
            return redirect()->route('admin.index');
        } else {
            $request->session()->flash('errors', ['login' => 'E-mail e/ou senha não conferem']);

            return redirect()->route('login')->withInput([
                'email' => $data['email']
            ]);
        };
    }

    public function register(Request $request)
    {
        return view('admin.register', [
            'errors' => $request->session()->get('errors'),
        ]);
    }

    public function registerAction(Request $request)
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

        if ($validator->fails()) {
            $request->session()->flash('errors', $validator->errors()->all());

            return redirect()->route('register')->withInput([
                'email' => $data['email']
            ]);
        } else {
            $email = $data['email'];
            $hash = password_hash($data['password'], PASSWORD_DEFAULT);

            $newUser = new User();

            do {
                $userPublicId = Str::uuid()->toString();
            } while (User::where('public_id', $userPublicId)->count() > 0);

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

    public function index()
    {
        $user = Auth::user();

        $pages = Page::where('id_user', $user->public_id)->get();

        return view('admin.index', [
            'pages' => $pages
        ]);
    }

    public function newPage()
    {
        $user = Auth::user();

        $pagesCount = Page::where('id_user', $user->public_id)->count();

        if ($pagesCount < 2) {
            return view('admin.newpage');
        } else {
            return redirect()->route('admin.index');
        }
    }

    public function newPageAction(Request $request)
    {
        $user = Auth::user();

        $pagesCount = Page::where('id_user', $user->public_id)->count();

        if ($pagesCount < 2) {
            $data = $request->only([
                'slug',
                'op_font_color',
                'op_bg_value_1',
                'op_bg_value_2',
                'op_profile_image',
                'op_title',
                'op_description',
            ]);

            $validator = Validator::make($data, [
                'slug' => [
                    'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                    'required',
                    'string',
                    'unique:pages',
                    'min:2',
                    'max:16',
                    Rule::notIn([
                        'admin',
                        'link'
                    ])
                ],
                'op_font_color' => ['required', 'regex:/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/i'],
                'op_bg_value_1' => ['required', 'regex:/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/i'],
                'op_bg_value_2' => ['required', 'regex:/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/i'],
                'op_profile_image' => ['image', 'mimes:png,jpg,jpeg,webp', 'max:10240'],
                'op_title' => ['max:100'],
                'op_description' => ['max:255'],
            ]);

            if ($validator->fails()) {
                $request->session()->flash('errors', $validator->errors()->all());

                return redirect()->route('admin.newpage')->withInput($data);
            } else {
                $op_profile_image = $data['op_profile_image'] ?? '';
                $op_title = $data['op_title'] ?? '';
                $op_description = $data['op_description'] ?? '';
                $slug = $data['slug'];
                $op_font_color = $data['op_font_color'];
                $op_bg_value_1 = $data['op_bg_value_1'];
                $op_bg_value_2 = $data['op_bg_value_2'];

                $newPage = new Page();

                do {
                    $pagePublicId = Str::uuid()->toString();
                } while (Page::where('public_id', $pagePublicId)->count() > 0);

                if ($op_profile_image) {
                    $imageName = $pagePublicId . '.webp';
                    $dest = public_path('/media/uploads/') . $imageName;

                    $manager = new ImageManager();

                    $img = $manager->make($op_profile_image->getRealPath())->fit(300, 300);
                    $img->save($dest);

                    $newPage->op_profile_image = $imageName;
                };

                if ($op_title) {
                    $newPage->op_title = $op_title;
                };

                if ($op_description) {
                    $newPage->op_description = $op_description;
                };

                $newPage->public_id = $pagePublicId;
                $newPage->id_user = $user->public_id;
                $newPage->slug = $slug;
                $newPage->op_font_color = $op_font_color;
                $newPage->op_bg_value = "$op_bg_value_1,$op_bg_value_2";
                $newPage->save();
            };
        };

        return redirect()->route('admin.index');
    }

    public function pageLinks(string $slug)
    {
        $user = Auth::user();

        $page = Page::where('slug', $slug)->where('id_user', $user->public_id)
            ->first();

        if ($page) {
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

        if (in_array($link->id_page, $myPages)) {
            if ($link->order > $pos) {
                $afterLinks = Link::where('id_page', $link->id_page)
                    ->where('order', '>=', $pos)->get();

                foreach ($afterLinks as $afterLink) {
                    $afterLink->order++;
                    $afterLink->save();
                };
            } else if ($link->order < $pos) {
                $beforeLinks = Link::where('id_page', $link->id_page)
                    ->where('order', '<=', $pos)->get();

                foreach ($beforeLinks as $beforeLink) {
                    $beforeLink->order--;
                    $beforeLink->save();
                };
            };

            $link->order = $pos;
            $link->save();

            $allLinks = Link::where('id_page', $link->id_page)
                ->orderBy('order', 'ASC')->get();

            foreach ($allLinks as $key => $item) {
                $item->order = $key;
                $item->save();
            };
        };
    }

    public function newLink(string $slug)
    {
        $user = Auth::user();

        $page = Page::where('id_user', $user->public_id)->where('slug', $slug)
            ->first();

        if ($page) {
            return view('admin.page_editlink', [
                'menu' => 'newLink',
                'page' => $page
            ]);
        } else {
            return redirect()->route('admin.index');
        };
    }

    public function newLinkAction(Request $request, string $slug)
    {
        $user = Auth::user();

        $page = Page::where('id_user', $user->public_id)->where('slug', $slug)
            ->first();

        if ($page) {
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

            if ($validator->fails()) {
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
                } while (Link::where('public_id', $linkPublicId)->count() > 0);

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

        if ($page) {
            $link = Link::where('public_id', $linkId)
                ->where('id_page', $page->public_id)->first();

            if ($link) {
                return view('admin.page_editlink', [
                    'menu' => 'editLink',
                    'page' => $page,
                    'link' => $link,
                ]);
            }
        };

        return redirect()->route('admin.index');
    }

    public function editLinkAction(Request $request, string $slug, string $linkId)
    {
        $user = Auth::user();

        $page = Page::where('id_user', $user->public_id)->where('slug', $slug)
            ->first();

        if ($page) {
            $link = Link::where('public_id', $linkId)
                ->where('id_page', $page->public_id)->first();

            if ($link) {
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

                if ($validator->fails()) {
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

    public function dellink(string $slug, string $linkId)
    {
        $user = Auth::user();

        $page = Page::where('id_user', $user->public_id)->where('slug', $slug)
            ->first();

        if ($page) {
            $link = Link::where('public_id', $linkId)
                ->where('id_page', $page->public_id)->first();

            Click::where('id_link', $link->public_id)->delete();

            if ($link) {
                $link->delete();

                $allLinks = Link::where('id_page', $page->public_id)
                    ->orderBy('order', 'ASC')->get();

                foreach ($allLinks as $key => $item) {
                    $item->order = $key;
                    $item->save();
                };

                return redirect()->route('admin.links', $slug);
            };
        };

        return redirect()->route('admin.index');
    }

    public function pageDesign(string $slug)
    {
        $user = Auth::user();

        $page = Page::where('id_user', $user->public_id)->where('slug', $slug)
            ->first();

        if ($page) {
            $page->op_bg_value = explode(',', $page->op_bg_value);

            return view('admin.page_design', [
                'menu' => 'design',
                'page' => $page
            ]);
        } else {
            return redirect()->route('admin.index');
        };
    }

    public function pageDesignAction(Request $request, string $pageSlug)
    {
        $user = Auth::user();

        $page = Page::where('slug', $pageSlug)->where('id_user', $user->public_id)
            ->first();

        if ($page) {
            $data = $request->only([
                'slug',
                'op_font_color',
                'op_bg_value_1',
                'op_bg_value_2',
                'op_profile_image',
                'op_title',
                'op_description',
            ]);

            $savedSlug = $data['slug'];

            if ($data['slug'] === $page->slug) {
                unset($data['slug']);
            };

            $validator = Validator::make($data, [
                'slug' => [
                    'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                    'string',
                    'unique:pages',
                    'min:2',
                    'max:16',
                    Rule::notIn(['admin'])
                ],
                'op_font_color' => ['regex:/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/i'],
                'op_bg_value_1' => ['regex:/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/i', 'required_with:op_bg_value_2'],
                'op_bg_value_2' => ['regex:/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/i', 'required_with:op_bg_value_1'],
                'op_profile_image' => ['image', 'mimes:png,jpg,jpeg,webp', 'max:10240'],
                'op_title' => ['max:100'],
                'op_description' => ['max:255'],
            ]);

            if ($validator->fails()) {
                $request->session()->flash('errors', $validator->errors()->all());

                return redirect()->route('admin.design', $pageSlug)->withInput($data);
            } else {
                $pageSlug = $savedSlug;

                $slug = $data['slug'] ?? '';
                $op_font_color = $data['op_font_color'] ?? '';
                $op_bg_value_1 = $data['op_bg_value_1'] ?? '';
                $op_bg_value_2 = $data['op_bg_value_2'] ?? '';
                $op_profile_image = $data['op_profile_image'] ?? '';
                $op_title = $data['op_title'] ?? '';
                $op_description = $data['op_description'] ?? '';

                if ($slug) {
                    $page->slug = $slug;
                };

                if ($op_font_color) {
                    $page->op_font_color = $op_font_color;
                };

                if ($op_bg_value_1 || $op_bg_value_2) {
                    $page->op_bg_value = "$op_bg_value_1,$op_bg_value_2";
                };

                if ($op_profile_image) {
                    $imageName = $page->public_id . '.webp';
                    $dest = public_path('/media/uploads/') . $imageName;

                    $manager = new ImageManager();

                    $img = $manager->make($op_profile_image->getRealPath())->fit(300, 300);
                    $img->save($dest);

                    $page->op_profile_image = $imageName;
                };

                if ($op_title) {
                    $page->op_title = $op_title;
                };

                if ($op_description) {
                    $page->op_description = $op_description;
                };

                $page->save();

                return redirect()->route('admin.links', $pageSlug);
            };
        };

        return redirect()->route('admin.index');
    }

    public function pageStats(string $slug)
    {
        $user = Auth::user();

        $page = Page::where('id_user', $user->public_id)->where('slug', $slug)
            ->first();

        if ($page) {
            $views = View::select([
                'view_date',
                'total',
            ])->where('id_page', $page->public_id)
                ->where('view_date', '>=', gmdate('Y-m-d', strtotime('-1 week')))
                ->orderBy('view_date')->get();

            $viewsData = [];
            $viewsLabel = [];

            $weekdays = [
                'Dom',
                'Seg',
                'Ter',
                'Qua',
                'Qui',
                'Sex',
                'Sáb',
            ];

            foreach ($views as $view) {
                array_push($viewsData, $view->total);

                $date = gmdate('d w', strtotime($view->view_date));
                $date = explode(' ', $date);

                $date[1] = $weekdays[$date[1]];

                $date = implode(' ', $date);

                array_push($viewsLabel, $date);
            };

            $viewsData = implode(', ', $viewsData);
            $viewsLabel = implode('", "', $viewsLabel);

            $viewsLabel = '"' . $viewsLabel;
            $viewsLabel .= '"';

            $links = Link::select([
                'public_id',
                'title'
            ])->where('id_page', $page->public_id)->orderBy('order', 'ASC')->get();

            $dateLastDay = gmdate('Y-m-d', strtotime('-1 day'));
            $dateLastWeek = gmdate('Y-m-d', strtotime('-1 week'));
            $dateLastMonth = gmdate('Y-m-d', strtotime('-1 month'));
            $dateLastYear = gmdate('Y-m-d', strtotime('-1 year'));

            foreach ($links as $linkKey => $link) {
                $links[$linkKey]->last_day = array_sum(
                    Click::where('id_link', $link->public_id)
                        ->where('click_date', '>=', $dateLastDay)->pluck('total')
                        ->toArray()
                );
                $links[$linkKey]->last_week = array_sum(
                    Click::where('id_link', $link->public_id)
                        ->where('click_date', '>=', $dateLastWeek)->pluck('total')
                        ->toArray()
                );
                $links[$linkKey]->last_month = array_sum(
                    Click::where('id_link', $link->public_id)
                        ->where('click_date', '>=', $dateLastMonth)->pluck('total')
                        ->toArray()
                );
                $links[$linkKey]->last_year = array_sum(
                    Click::where('id_link', $link->public_id)
                        ->where('click_date', '>=', $dateLastYear)->pluck('total')
                        ->toArray()
                );
            };

            return view('admin.page_stats', [
                'menu' => 'stats',
                'page' => $page,
                'links' => $links,
                'viewsLabel' => $viewsLabel,
                'viewsData' => $viewsData,
            ]);
        } else {
            return redirect()->route('admin.index');
        }
    }

    public function pageDelete(string $slug)
    {
        $user = Auth::user();

        $page = Page::where('id_user', $user->public_id)->where('slug', $slug)
            ->first();

        if ($page) {
            $links = Link::where('id_page', $page->public_id)->get();

            foreach ($links as $link) {
                Click::where('id_link', $link->public_id)->delete();

                $link->delete();
            };


            View::where('id_page', $page->public_id)->delete();

            if ($page->op_profile_image !== 'default.webp') {
                unlink(public_path('/media/uploads/' . $page->op_profile_image));
            };

            $page->delete();

            return redirect()->route('admin.index');
        } else {
            return redirect()->route('admin.index');
        }
    }

    public function accountConfig()
    {
        $user = Auth::user();

        return view('admin.account', [
            'user' => $user,
        ]);
    }

    public function accountConfigAction(Request $request)
    {
        $user = Auth::user();

        $data = $request->only([
            'email',
            'password',
            'password_confirmation'
        ]);

        if ($data['email'] === $user->email) {
            unset($data['email']);
        };

        if (empty($data['password'])) {
            unset($data['password']);
        };

        $validator = Validator::make($data, [
            'email' => ['string', 'email', 'unique:users', 'max:50'],
            'password' => ['string', 'min:8', 'confirmed']
        ]);

        if ($validator->fails()) {
            $request->session()->flash('errors', $validator->errors()->all());

            return redirect()->route('admin.account');
        } else {
            $email = $data['email'] ?? '';
            $password = $data['password'] ?? '';

            $changed = false;

            if ($email || $password) {
                $changed = true;
            };

            if ($changed) {
                $modifiedUser = User::where('public_id', $user->public_id)->first();

                if ($email) {
                    $modifiedUser->email = $email;
                };

                if ($password) {
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $modifiedUser->password = $hash;
                };

                $modifiedUser->save();

                $request->session()->flash('success', 'Alterações salvas com sucesso!');
            }

            return redirect()->route('admin.account');
        };
    }
}
