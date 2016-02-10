@extends('common.master')
@section('content')
    
    <section class="mainWidth">
    {{ Form::open(array('url' => 'saveRegisterInfo' , 'method' => '  post')) }}
        <div class="col-sm-10 centralize clearfix">
            <h3>To complete registration, please enter the following information</h3>
            <div class="registrFrm col-md-12">

                <!-- <h6>This information allows people, who receive good karma from you, to create awareness (or donate) for your cause.</h6> -->
                <div class="frmBox">
                     
                        <div class="form-group clearfix">
                         {{ Form::label('comments', 'First Name');}}
                         {{ Form::text('fname', $fname_final, array('class'=>'width33 form-control','pattern'=>'[A-Za-z]+','title'=>"Name can only contain alphabets",'required'=>'required'))}}
                         {{ Form::label('comments', 'Last Name');}}
                         {{ Form::text('lname', $lname_final, array('class'=>'width33 form-control','pattern'=>'[A-Za-z]+','title'=>"Name can only contain alphabets",'required'=>'required'))}}
                      </div>
                      
                    <!-- <div class="form-group"> 
                       {{ Form::label('comments', 'I love to help with (Optional)');}}
                             {{ Form::textarea('comments','',array('class'=>'form-control','rows'=>'4','placeholder'=>'Marketing, Product, Strategy, Pricing, etc')); }}
                               {{$errors->first('comments','<span class=error>:message</span>')}}
                        
                    </div> -->
                    <div >  {{Form::checkbox('termsofuse[]',"accept", true,array('required'=>'required')); }}<span> I agree to KarmaCircles.com <a href="/terms" target="_blank">Terms And Privacy Policy</a></span></div>
                       
                     {{$errors->first('termsofuse',"<span class=error>Please agree to KarmaCircles User Agreement, Privacy Policy</span>")}}
                     
                      <div > {{Form::checkbox('shareOnLinkedin',"1",true)}}<span> Share this on LinkedIn  </div>
                     
                    
                      </div>
                   
                <div align="center">
                    <a href="logout"> {{Form::button('Cancel',array('class'=>'btn btn-warning'));}}</a>
                    {{Form::submit('Complete Registration',array('class'=>'btn btn-success'));}}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </section>
    <!-- /Main colom -->
@stop
    
