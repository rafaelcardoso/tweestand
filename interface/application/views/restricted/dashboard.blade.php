<?php $user = Session::get('myauth_login');?>
<div class="container">
    <div class="container-fluid">
        <div class="row-fluid">
            
            <div class="span12">

                @include('includes.messages')
                
                @if(empty($user['twitter_accounts']))

                    @if(array_key_exists('auth_url',$user))
                        <div class="hero-unit">
                            <h1>Welcome {{ ucwords(explode(' ',trim($user['name']))[0]) }}</h1>
                            <p>We are very excited to have you here. To get started, you need to add your twitter account.</p>
                            <a href="{{ $user['auth_url'] }}" class="btn btn-info btn-large">Add a twitter account</a>
                        </div>
                    @endif

                @else
                
                    <?$find = false?>
                    @foreach($user['twitter_accounts'] as $account)
                        @if($account['enable'] == 1)
                            <?$find = true?>
                            
                            <ul class="nav nav-tabs" id="report-tab">
                                <li class="active"><a href="#reports">Reports</a></li>
                                <li><a href="#followers">Followers</a></li>
                            </ul>
                                 
                            <div class="tab-content">

                                <div class="tab-pane active clearfix" id="reports">
                                    
                                    <div class="reportrange-container clearfix">
                                        <div id="reportrange" class="date-picker pull-right">
                                            <i class="icon-calendar icon-large"></i>
                                            <span>Choose the date range</span> <b class="caret"></b>
                                        </div>    
                                    </div>
                                    
                                    <div id="engagement-report" class="report clearfix">
                                        <h2>Engagement Report</h2>
                                        <p class="muted">
                                            <span class="definition">Waiting  the date range.</span>
                                            <span class="from-date"></span>
                                            <span class="separator"></span>
                                            <span class="to-date"></span>
                                        </p>
                                        <div id="engagement-chart" class="chart-report"></div>
                                        <table id="engagement-table" class="table table-striped table-bordered table-report table-fixed-header">
                                            <thead class="header">
                                                <tr> 
                                                    <th>date</th> 
                                                    <th>tweets</th>
                                                    <th>mentions</th>
                                                    <th>retweets</th>
                                                </tr> 
                                            </thead> 
                                            <tbody></tbody> 
                                        </table>
                                    </div>
                                    
                                    <div id="influence-report" class="report clearfix">
                                        <h2>Influence Report</h2>
                                        <p class="muted">
                                            <span class="definition">Waiting  the date range.</span>
                                            <span class="from-date"></span>
                                            <span class="separator"></span>
                                            <span class="to-date"></span>
                                        </p>
                                        <div id="influence-chart" class="chart-report"></div>
                                        <table id="influence-table" class="table table-striped table-bordered table-report table-fixed-header">
                                            <thead class="header">
                                                <tr> 
                                                    <th>date</th> 
                                                    <th>mentions received</th>
                                                    <th>retweets receveid</th>
                                                </tr> 
                                            </thead> 
                                            <tbody></tbody> 
                                        </table>
                                    </div>
                                    
                                    <div id="followers-report" class="report clearfix">
                                        <h2>Followers Report</h2>
                                        <p class="muted">
                                            <span class="definition">Waiting  the date range.</span>
                                            <span class="from-date"></span>
                                            <span class="separator"></span>
                                            <span class="to-date"></span>
                                        </p>
                                        <div id="followers-chart" class="chart-report"></div>
                                        <table id="followers-table" class="table table-striped table-bordered table-report table-fixed-header">
                                            <thead class="header">
                                                <tr>
                                                    <th>date</th> 
                                                    <th>won followers</th>
                                                    <th>lost followers</th>
                                                    <th>followers count</th>
                                                </tr> 
                                            </thead> 
                                            <tbody></tbody> 
                                        </table>
                                    </div>
                                    
                                </div>

                                <div class="tab-pane clearfix" id="followers">
                                    
                                    <div class="report clearfix">
                                        <h2>Map of followers</h2>
                                        <p class="muted">approximate location of your followers.</p>
                                        <div id="followers-map-progress" class="progress progress-striped active">
                                            <div class="bar" data-progress="0%" style="width: 0%;">loading</div>
                                        </div>
                                        <div id="followers-map" class="map"></div>
                                    </div>
                                </div>
                                
                            </div>

                        @endif
                    @endforeach
                    @if(!$find)
                        <div class="alert alert-error">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            you do not have any twitter account active, you must enable at least one to perform this action. Your account may have been disabled by clicking the disable account button or revoking the tweestand access to your twitter. make sure that you have no disabled any twitter account and activate it on "manage twitter accounts", if all are active go to twitter and authorize the tweestand to use your account again on settings > applications. (the second solution can take up to 24 hours to take effect).
                        </div>
                    @endif

                @endif

            </div>
        </div>
    </div>
</div>

<script>

if(typeof(JSON) == "undefined"){
    head.js({json3: "{{ URL::base() }}/js/libs/json3.min.js"});
}



head.ready("jquery", function() {

    @if(!empty($user['twitter_accounts']))
        window.twitterIdentification = {{$user['twitter_accounts'][0]['identification']}};

        @if(!empty($user['twitter_accounts'][0]['followers']))
            head.ready("amplify", function(){
                var storedData = amplify.store(window.twitterIdentification);
                if(!storedData){
                    storedData = {};
                    storedData.followers = {{json_encode($user['twitter_accounts'][0]['followers'], true)}};
                    amplify.store(window.twitterIdentification, storedData, {expires: 21600000});
                }else{
                    storedData.followers = {{json_encode($user['twitter_accounts'][0]['followers'], true)}};
                    amplify.store(window.twitterIdentification, storedData, {expires: 21600000});
                }
            });

            <?
            unset($user['twitter_accounts'][0]['followers']);
            Session::put('myauth_login', $user); 
            ?>

        @endif

        head.ready("daterangepicker", function() {
            var today = Date.today(),
                yesterday = today.clone().add({ days: -1 });
            $("#reportrange").daterangepicker({
                format: "yyyy-MM-dd",
                startDate: yesterday,
                endDate: yesterday,
                maxDate: today,
                ranges: {
                    "Yesterday": [yesterday, yesterday],
                    "Last 7 Days": [today.clone().add({ days: -6 }), "today"],
                    "Last 30 Days": [today.clone().add({ days: -29 }), "today"]
                }
            }, function(fromDate, toDate) {
                if(fromDate && toDate) {
                    $('#reportrange span').text(fromDate.clone().toString('yyyy-MM-dd') + ' - ' + toDate.clone().toString('yyyy-MM-dd'));
                    tweestand.report.get(fromDate, toDate, {{$user['twitter_accounts'][0]['identification']}});
                }
            });
        });

        head.ready("report", function() {

        if(tweestand.helpers.device.mobile === false) {
            head.js({highcharts: "{{ URL::base() }}/js/libs/highcharts.min.js"});
        }

        $('#report-tab a:first').tab('show');
        $('#report-tab a').click(function(e) {
            e.preventDefault();
            $(this).tab('show');
            if($(this).text() == 'Followers'){
                $.each(tweestand.report.maps.src, function(index, object) {
                    google.maps.event.trigger(object.map, 'resize');
                });
            }
        });
    });


    
    head.js(
        {date: "{{ URL::base() }}/js/libs/date.min.js"},
        {amplify: "{{ URL::base() }}/js/libs/amplify.min.js"},
        {daterangepicker: "{{ URL::base() }}/js/libs/daterangepicker.min.js"},
        {report: "{{ URL::base() }}/js/report.js"},
        {markerclusterer: "{{ URL::base() }}/js/libs/markerclusterer.js"},
        {googlemaps: "https://maps.googleapis.com/maps/api/js?key=AIzaSyBGW3sNv5K3lAaPvGa0UzA5rUPkrn0XeaU&sensor=false&callback=tweestand.init"}
    );


    @endif

    
    
    
});
</script>