<?php

namespace Laravolt\Comma\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Laravolt\Comma\Exceptions\CmsException;
use Laravolt\Comma\Http\Requests\StorePost;
use Laravolt\Comma\Http\Requests\UpdatePost;
use Laravolt\Comma\Models\Scopes\VisibleScope;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = app('laravolt.comma.models.post')->autoSort()->latest()->search($request->get('search'))->paginate();

        return view('comma::posts.index', compact('posts'));
    }

    public function create()
    {
        $tags = app('laravolt.comma.models.tag')->all()->pluck('name', 'name');

        return view('comma::posts.create', compact('tags'));
    }

    public function store(StorePost $request)
    {
        try {
            $post = app('laravolt.comma')
                ->create(
                    $request->get('title'),
                    $request->get('content'),
                    auth()->user(),
                    'post',
                    $request->get('tags')
                );

            return redirect()->route('comma::posts.index')->withSuccess(trans('comma::post.message.create_success'));
        } catch (CmsException $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $post = app('laravolt.comma.models.post')->findOrFail($id);
        $tags = app('laravolt.comma.models.tag')->pluck('name', 'name');

        return view('comma::posts.edit', compact('post', 'tags'));
    }

    public function update(UpdatePost $request, $id)
    {
        $post = app('laravolt.comma.models.post')->findOrFail($id);

        try {
            $post = app('laravolt.comma')
                ->update(
                    $post,
                    $request->get('title'),
                    $request->get('content'),
                    auth()->user(),
                    $request->get('tags')
                );

            if ($request->hasFile('featured_image')) {
                $post->clearMediaCollection('featured');
                $post->addMediaFromRequest('featured_image')->toMediaCollection('featured');
            }

            switch ($request->get('action')) {
                case 'publish':
                    $post->publish();
                    break;
                case 'unpublish':
                    $post->unpublish();
                    break;
                case 'draft':
                    $post->saveAsDraft();
                    break;
                case 'save':
                default:
                    break;
            }

            return redirect()->back()->withSuccess(trans('comma::post.message.update_success'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            app('laravolt.comma.models.post')->find($id)->delete();

            return redirect()->route('comma::posts.index')->withSuccess(trans('comma::post.message.delete_success'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }
}
