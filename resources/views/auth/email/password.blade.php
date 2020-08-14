點此重設你的密碼: <a href="{{ $link = url('password/reset',$token).
'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a>
