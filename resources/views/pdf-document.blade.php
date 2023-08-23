<!DOCTYPE html>
<html>
<head>
  <title>{{$document->name}}</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
</head>
<body>
  <div class="container">
    <div class="row">
      <div class="col-12 mt-2">
        <div class="pull-left">
          <h1>{{$document->name}}</h1>
        </div>
      </div>
    </div>
    <hr>
    @forelse($document->columns as $column)
        <div class="row">
          <div class="col-12 mb-2em border-bottom">
            <div class="pull-left">
              <h2>{{$column->name}}</h2>
              <p class="text-center">{{$column->pivot->content}}</p>
            </div>
          </div>
        </div>
    @empty
        <h4>This document has no content</h4>
    @endforelse
  </div>
</body>
</html>
