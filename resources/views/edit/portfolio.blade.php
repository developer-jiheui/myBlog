@extends('layouts.footer')
@extends('layouts.main')
@section('content')
    @php
        if (isset($_GET['id'])) {
            $item = \App\Models\Portfolio::find($_GET['id']);
            if (Auth::user()===null||Auth::user()->id!=$item['user_id']) {
                http_response_code(304); // FORBIDDEN // TODO return a page instead of just blank content
                die();
            }
        }
        else {
            $item = [];
            if (Auth::user()===null||Auth::user()->user_type!=0) {
                http_response_code(304);
                die();
            }
        }
    @endphp
    <article class=active data-page="portfolio">

        <header>
            <h2 class="h2 article-title">
                @if(isset($_GET['id']))
                    Editing Item
                @else
                    Adding Item
                @endif
            </h2>
        </header>
        <form class=form
              action="{{isset($_GET['id'])?route('edit.portfolio.update', ['id'=>$_GET['id']]):route('edit.portfolio.create')}}"
              enctype=multipart/form-data>
            @csrf
            @if(isset($_GET['id']))
                @method('patch')
            @else
                @method('post')
            @endif

            <div class="input-wrapper">
                <label for=title class=form-label>Title</label>
                <input name=title id=title class=form-input value="{{$item['title']??''}}" required>
            </div>
            <div class="input-wrapper">
                <label for=desc class=form-label>Description</label>
                <textarea name=desc id=desc class=form-input required>{{$item['description']??''}}</textarea>
            </div>
            <div class="input-wrapper">
                <label for=url class=form-label>URL</label>
                <input name=url id=url class=form-input type=url value="{{$item['project_url']??''}}" required>
            </div>
            <div class="input-wrapper">
                <label for=category class=form-label>Category</label>
                <input name=category id=category class=form-input list=categories value="{{$item['category']??''}}"
                       required>
            </div>
            <datalist id=categories>
                @foreach (\App\Models\Portfolio::categories() as $category)
                    <option value="{{$category}}">
                @endforeach
            </datalist>
            <div class="input-wrapper">
                <label for=img class=form-label>Image</label>
                <div style=display:flex;justify-content:space-between;align-items:center>
                    <span id=imgname>{{ltrim(strrchr($item['image_url']??'/No image','/'),'/')}}</span>
                    <!-- NOTE text overflowing is a possibility but probably not important since this is just a demo -->
                    <label class=icon-box>
                        <ion-icon name="cloud-upload-outline" role=img aria-label="Upload new icon&hellip;"></ion-icon>
                        <input type=file name=img id=img style=position:absolute;top:-999px
                               onchange="document.getElementById('imgname').textContent=this.files[0].name">
                    </label></div>
            </div>

            <div>
                <button type="submit" class="form-btn" style=margin-right:auto>
                    @if(isset($_GET['id']))
                        Save Item
                    @else
                        Add Item
                    @endif
                </button>
            </div>
        </form>
    </article>
@endsection
@extends('layouts.header')
