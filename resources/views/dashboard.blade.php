@extends('layouts.app')

@section('sidebar')
  <sidebar></sidebar>
@endsection

@section('content')
  <latest-posts></latest-posts>

  <bot-modal></bot-modal>
  <channel-modal></channel-modal>
  <post-modal></post-modal>
  <user-profile-modal></user-profile-modal>
@endsection
