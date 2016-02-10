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
    {{ Form::open(array('url' => 'saveAdviceInfo' , 'method' => '  post')) }}
        <div class="col-sm-10 centralize clearfix">
            <h3>Update</h3>
            <div class="registrFrm col-md-12">
                <!-- <h6>This information allows people, who receive good karma from you, to create awareness (or donate) for your cause.</h6> -->
                <div class="frmBox">
                   
                    <div class="form-group clearfix">  
                      {{ Form::label('noofmeetingspm', 'How many people do you want to help per month?', array('class'=>'col-md-12 col-xs-12'));}}
                       <div class="select col-xs-3 col-sm-1 col-md-1 pdding0 clearfix">
                        <span class="pointer"></span>
                        @if ($CurrentUser->termsofuse =='0')
                             {{ Form::select('noofmeetingspm', array('1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8','9' => '9', '10' => '10' ),array('default' => '4'),array('class'=>'form-control')); }}
                        @else
                            {{ Form::select('noofmeetingspm', array('1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8','9' => '9', '10' => '10' ),array('default' => $CurrentUser->noofmeetingspm),array('class'=>'form-control')); }}
                        @endif
                        </div>    
                    </div>
                    <div class="form-group">
                       {{ Form::label('comments', 'I love to help with (Optional)');}}
                       
                        @if ($CurrentUser->termsofuse =='0')
                             {{ Form::textarea('comments','',array('class'=>'form-control','rows'=>'4')); }}
                        @else
                             {{ Form::textarea('comments',$CurrentUser->comments,array('class'=>'form-control','rows'=>'4','placeholder'=>'Marketing, Product, Strategy, Pricing, etc')); }}
                        @endif
                               {{$errors->first('comments','<span class=error>:message</span>')}}
                        <!-- <span class="complrsy">*</span> -->
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
    
