@extends('common.master')
@section('content')
    <section class="mainWidth greenBg ldprofile clearfix">
        <div class=" col-sm-10 centralize ">
            <div class="col-sm-3 col-xs-3">
              <div class="dpcontainer">
               
                @if ($CurrentUser->piclink == '')
                  <img alt="" src="/images/default.png">
                @else
                <img src="{{ $CurrentUser->piclink;}}" class="img-responsive"  alt = "{{$CurrentUser->fname;}}" title = "{{$CurrentUser->fname;}}">
                @endif 
                
               <!--  <img src="images/linkdin.png" class="linkdinIcon"> -->
              </div>  
            </div>
            <div class="col-sm-4  col-xs-5 profileDeatils">
                <h2>{{ $CurrentUser->fname.' '.$CurrentUser->lname ;}}</h2>
                <p>{{ $CurrentUser->headline;}}</p>
                <p>{{ $CurrentUser->location;}}</p>
            </div>
        </div>
    </section>
    <section class="mainWidth">
    {{ Form::open(array('url' => 'saveCauseInfo' , 'method' => '  post')) }}
        <div class="col-sm-10 centralize clearfix">
            <h3>Update Cause</h3>
            <div class="registrFrm col-md-12">
                <!-- <h6>This information allows people, who receive good karma from you, to create awareness (or donate) for your cause.</h6> -->
                <div class="frmBox">
                    <div class="form-group">
                        {{ Form::label('causesupported', 'Name of the cause you are supporting'); }}
                          @if ($CurrentUser->termsofuse =='0')
                               {{ Form::text('causesupported','',array('class'=>'form-control')); }}
                          @else
                            {{ Form::text('causesupported',$CurrentUser->causesupported,array('class'=>'form-control')); }}
                        @endif
                    </div>
                    <div class="form-group">
                        {{ Form::label('urlcause', 'URL to be shared on social media (or to donate)');}}
                        @if ($CurrentUser->termsofuse =='0')
                              {{ Form::text('urlcause','',array('class'=>'form-control')); }}
                        @else
                             {{ Form::url('urlcause',$CurrentUser->urlcause,array('class'=>'form-control','placeholder'=>'http://example.com')); }}
                        @endif
                          {{$errors->first('urlcause','<span class=error>:message</span>')}}
                    </div> 
                    <div class="form-group clearfix">
                      {{ Form::label('donationtypeforcause', 'I would appreciate the following in return for my time');}}
                       <div class="select col-sm-10 pdding0">
                        <span class="pointer"></span>
                         @if ($CurrentUser->termsofuse =='0')
                              {{ Form::select('donationtypeforcause', array('One dollar' => 'To thank me, you could also donate a dollar to my cause.', 'One minute' => 'To thank me, you could also take a minute and spread awareness about my cause on social media.'),'One dollar',array('class'=>'form-control')); }}
                        @else
                             {{ Form::select('donationtypeforcause', array('One dollar' => 'To thank me, you could also donate a dollar to my cause.', 'One minute' => 'To thank me, you could also take a minute and spread awareness about my cause on social media.'),array('default'=>$CurrentUser->donationtypeforcause),array('class'=>'form-control')); }}
                        @endif
                        </div>     
                    </div>
                <div align="center">
                      <a href="{{URL::previous()}}"> {{Form::button('Cancel',array('class'=>'btn btn-warning','onclick'=>'test()'));}}</a>
                      {{Form::submit('Submit',array('class'=>'btn btn-success'));}}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </section>
    <!-- /Main colom -->
@stop
    
