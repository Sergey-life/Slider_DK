<div class="container js-articles">
    <div class="js-checkbox">
        <form class="js-form" action="{{route('articles')}}" method="GET">
            @csrf
            <p><strong>Теми</strong></p>
            <fieldset>
                @foreach($topics as $topic)
                    <input id="topic" type="checkbox" name="topics[]" value="{{$topic->id}}" @if(isset($checkedTopics) && in_array($topic->id, $checkedTopics)) checked @endif>
                    <label for="topic">{{$topic->name}}</label><br>
                @endforeach
            </fieldset>
            <hr>
            <p><strong>Теги</strong></p>
            <fieldset>
                @foreach($tags as $tag)
                    <input id="tag" type="checkbox" name="tags[]" value="{{$tag->id}}" @if(isset($checkedTags) && in_array($tag->id, $checkedTags)) checked @endif>
                    <label for="tag">{{$tag->name}}</label><br>
                @endforeach
            </fieldset>
        </form>
    </div>
    <div class="row">
        @foreach($articles as $article)
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <img src="{{ $article->image }}" alt="image">
                    <div class="card-body">
                        <h5>{{ Str::limit($article->title, 20) }}</h5>
                        <p class="card-text">{{ Str::limit($article->body, 80) }}</p>
                        @foreach($article->tags as $tag)
                            <div class="btn-group tags-name-block">
                                <div class="tags-name">{{ $tag->name }}</div><br>
                            </div>
                        @endforeach
                        <div>
                            {{$article->topic->name}}
                        </div>
                        <div>
                            {{$article->id}}
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-outline-secondary">View</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                            </div>
                            <small class="text-muted">{{ $article->created_at }}</small>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
