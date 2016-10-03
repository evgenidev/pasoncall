@extends('layouts.app')

@section('content')
    <div class="page-content">
        <div class="row">
          <div class="col-md-2">
            <div class="sidebar content-box" style="display: block;">
                <ul class="nav">
                    <!-- Main menu -->
                    <li class="current"><a href="/home"><i class="glyphicon glyphicon-home"></i> Dashboard</a></li>    
                </ul>
             </div>
          </div>
          @if (Auth::user()->type == 1)
            @include('dashboard.client')
          @else
            @include('dashboard.transcriptor')
          @endif

          </div>
        </div>
    </div>

@endsection
