var tweestand = {

    init: function(){

        this.amplify.ajax.decoders();
        this.amplify.ajax.definers();
        this.helpers.arrayLast();
        tweestand.helpers.isMobile();
        tweestand.report.maps.src.followers.locationLoaderBar.setOnePerCent(window.twitterIdentification);
        this.report.maps.load();
        this.report.maps.geo.setFollowersLocation(window.twitterIdentification);
        delete window.twitterIdentification;
        
    },

    report: {

        get: function(fromDate, toDate, twitterIdentification) {
            
            var temporaryData = {};
            temporaryData.dateRange = tweestand.helpers.getDateRange(new Date(fromDate), new Date(toDate));
            if(temporaryData.dateRange.length > 30){

                var error = {
                    msg: "Sorry, but the number of days limit is 30 per report. ",
                    optional: $('<a href="/report" class="btn btn-mini btn-info" type="button">help us improve this</a>')
                };

                tweestand.helpers.putCommonError(error);
                return false;
            }
            temporaryData.storedReport = this.exists(temporaryData.dateRange, twitterIdentification);

            fromDate = fromDate.toString('yyyy-MM-dd');
            toDate = toDate.toString('yyyy-MM-dd');

            if(!temporaryData.storedReport.exists) {

                amplify.request({
                    resourceId: "report",
                    data: {
                        from: fromDate,
                        to: toDate
                    },
                    success: function(data) {
                        
                        var storedData = amplify.store(twitterIdentification);
                        
                        if(storedData){

                            if(storedData.reports){
                                for (var i = 0; i < data.report.length; i++) {
                                    var find = false;
                                    for (var j = 0; j < storedData.reports.length; j++) {
                                        if(storedData.reports[j].date == data.report[i].date) {
                                            find = true;
                                        }
                                    }
                                    if(!find){
                                        
                                        storedData.reports[storedData.reports.length] = data.report[i];
                                        amplify.store(twitterIdentification, storedData, {expires: 21600000});
                                    }
                                }

                                
                                
                                
                            } else{
                                storedData.reports = data.report;
                                amplify.store(twitterIdentification, storedData, {expires: 21600000});
                            }
                        }

                        tweestand.report.configValues(data.report);
                    },
                    error: function(errors) {
                        if(typeof(errors) == 'string') {
                            tweestand.helpers.putCommonError(errors);
                        } else {
                            $.each(errors, function(i, obj) {
                                $.each(obj, function(j, text) {
                                    error = {};
                                    error.msg = text;
                                    tweestand.helpers.putCommonError(error);
                                });
                            });
                        }
                    }
                });

            } else {
                tweestand.report.configValues(temporaryData.storedReport.source);
            }

            delete temporaryData.dateRange;
            delete temporaryData.storedReport;

            return true;
        },

        exists: function(dates, twitterIdentification) {

                var store = amplify.store(twitterIdentification),
                    hits = 0,
                    reports = [],
                    result = [];
                    result.exists = false;
                    result.source = null;
                
                if(store) {
                    store = store.reports;
                    for (i = 0; i < dates.length; i++) {
                        for (var report in store) {
                            if (store[report].date == dates[i]) {
                                hits++;
                                reports.push(store[report]);
                                break;
                            }
                        }
                    }    
                }

                if (dates.length === hits) {
                    result.exists = true;
                    result.source = reports;
                }

                return result;
        
        },

        configValues: function(data) {
            
                var dates = [],
                    tweetsSent = [],
                    retweetsSent = [],
                    retweetsReceveid = [],
                    mentionsSent = [],
                    mentionsReceived = [],
                    followersCount = [],
                    lostFollowers = [],
                    wonFollowers = [];

                $.each(data, function(index, val) {
                    dates.push(val.date);
                    
                    tweetsSent.push(parseInt(val.tweets_sent_count, 10));
                    mentionsSent.push(parseInt(val.mentions_sent_count, 10));
                    retweetsSent.push(parseInt(val.retweets_sent_count, 10));
                    retweetsReceveid.push(parseInt(val.retweets_receveid_count, 10));
                    mentionsReceived.push(parseInt(val.mentions_received_count, 10));
                    followersCount.push(parseInt(val.followers_count, 10));
                    lostFollowers.push(val.lost_followers.length);
                    wonFollowers.push(val.won_followers.length);
                });

                var reportData = {
                    dates: dates,
                    tweetsSent: tweetsSent,
                    retweetsSent: retweetsSent,
                    retweetsReceveid: retweetsReceveid,
                    mentionsSent: mentionsSent,
                    mentionsReceived: mentionsReceived,
                    followersCount: followersCount,
                    lostFollowers: lostFollowers,
                    wonFollowers: wonFollowers
                };

                var fromDate = new Date(reportData.dates[0]).toString('MMMM d, yyyy'),
                    toDate = new Date(reportData.dates.last()).toString('MMMM d, yyyy');

                $("#engagement-report .definition").text("Everything you did from ");
                $("#engagement-report .from-date").text(fromDate);
                $("#engagement-report .to-date").text(toDate);

                $("#influence-report .definition").text("Everything you received from ");
                $("#influence-report .from-date").text(fromDate);
                $("#influence-report .to-date").text(toDate);

                $("#followers-report .definition").text("Your followers history from ");
                $("#followers-report .from-date").text(fromDate);
                $("#followers-report .to-date").text(toDate);

                $(".separator").text(" to ");

                if(tweestand.helpers.device.mobile) {
                    return this.paintMobile(reportData);
                } else {
                    return this.paintDesktop(reportData);
                }
                

        },

        paintDesktop: function(reportData) {

            $('.table-report').css('display','none');
            $('.chart-report').css('display','block');
                    
            var engagementChart = new Highcharts.Chart({
                chart: {
                    renderTo: 'engagement-chart',
                    type: 'spline',
                    zoomType: 'x'
                },
                title: {
                    margin: 30,
                    y: 30,
                    text: '',
                    style: {
                        color: '#999',
                        fontSize: '18px'
                    }
                },
                xAxis: {
                    categories: reportData.dates,
                    labels: {
                        rotation: -45,
                        align: 'right'
                    }
                },
                yAxis: {
                    allowDecimals: false,
                    min: 0,
                    title: {
                        text: ''
                    }
                },
                tooltip: {
                    crosshairs: true,
                    shared: true
                },
                series: [
                    {
                        name: 'tweets sent',
                        data: reportData.tweetsSent
                    }, {
                        name: 'mentions sent',
                        data: reportData.mentionsSent
                    }, {
                        name: 'retweets sent',
                        data: reportData.retweetsSent
                    }
                ]
            });

            var influenceChart = new Highcharts.Chart({
                chart: {
                    renderTo: 'influence-chart',
                    type: 'spline',
                    zoomType: 'x'
                },
                title: {
                    margin: 30,
                    y: 30,
                    text: '',
                    style: {
                        color: '#999',
                        fontSize: '18px'
                    }
                },
                xAxis: {
                    categories: reportData.dates,
                    labels: {
                        rotation: -45,
                        align: 'right'
                    }
                },
                yAxis: {
                    allowDecimals: false,
                    min: 0,

                    title: {
                        text: ''
                    }
                },
                tooltip: {
                    crosshairs: true,
                    shared: true
                },
                series: [
                    {
                        name: 'mentions received',
                        data: reportData.mentionsReceived
                    },
                    {
                        name: 'retweets receveid',
                        data: reportData.retweetsReceveid
                    }
                ]
            });

            var followersChart = new Highcharts.Chart({
                chart: {
                    renderTo: 'followers-chart',
                    type: 'spline',
                    zoomType: 'x'
                },
                title: {
                    margin: 30,
                    y: 30,
                    text: '',
                    style: {
                        color: '#999',
                        fontSize: '18px'
                    }
                },
                xAxis: {
                    categories: reportData.dates,
                    labels: {
                        rotation: -45,
                        align: 'right'
                    }
                },
                yAxis: {
                    allowDecimals: false,
                    min: 0,
                    title: {
                        text: ''
                    }
                },
                tooltip: {
                    crosshairs: true,
                    shared: true
                },
                series: [
                    {
                       name: 'total followers',
                       data:  reportData.followersCount
                    },
                    {
                       name: 'lost followers',
                       data:  reportData.lostFollowers
                    },
                    {
                       name: 'won followers',
                       data:  reportData.wonFollowers
                    }
                ]
            });

        },

        paintMobile: function(reportData) {

            $('.chart-report').css('display','none');
            $('#engagement-table tbody').empty();

            var engagementLine = new Array(reportData.dates.length),
                influenceLine  = new Array(reportData.dates.length),
                followersLine  = new Array(reportData.dates.length);
                
            for(i=0;i<reportData.dates.length;i++) {
                
                engagementLine[i] = $('<tr>')
                .append($('<td>').text(reportData.dates[i]))
                .append($('<td>').text(reportData.tweetsSent[i]))
                .append($('<td>').text(reportData.mentionsSent[i]))
                .append($('<td>').text(reportData.retweetsSent[i]));

                influenceLine[i] = $('<tr>')
                .append($('<td>').text(reportData.dates[i]))
                .append($('<td>').text(reportData.mentionsReceived[i]))
                .append($('<td>').text(reportData.retweetsReceveid[i]));
                
                followersLine[i] = $('<tr>')
                .append($('<td>').text(reportData.dates[i]))
                .append($('<td>').text(reportData.wonFollowers[i]))
                .append($('<td>').text(reportData.lostFollowers[i]))
                .append($('<td>').text(reportData.followersCount[i]));

            }

            $('#engagement-table').append(engagementLine);
            $('#influence-table').append(influenceLine);
            $('#followers-table').append(followersLine);

            $('.table-report').css('display','table');
            $('.table-fixed-header').fixedHeader();

        },

        maps: {

            load: function() {

                if ($("#followers-map").length > 0){
                    this.geo.coder = new google.maps.Geocoder();
                    this.src.followers.map = new google.maps.Map(document.getElementById("followers-map"),{
                        center: new google.maps.LatLng(71, 150),
                        zoom: 2,
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    });

                    this.src.followers.cluster = new MarkerClusterer(this.src.followers.map);
                }
            },
            src: {

                followers:{

                    map:{

                    },

                    cluster:{

                    },

                    locationLoaderBar: {

                        onePerCent: null,
                        totalFollowers: 0,
                        timeOut: null,

                        getLoaderBar: function() {
                            return $("#followers-map-progress");
                        },

                        show: function() {
                            return this.getLoaderBar().show();
                        },

                        setOnePerCent: function(twitterIdentification) {
                           
                            if(amplify.store(twitterIdentification) && amplify.store(twitterIdentification).followers) {
                                var followersLength = amplify.store(twitterIdentification).followers.length;
                                this.onePerCent = tweestand.helpers.perCentOf(1, followersLength);
                                this.setTotal(followersLength);
                            }
                            
                        },

                        setTotal: function(followersLength){
                            this.totalFollowers = followersLength;
                        },

                        update: function(index) {

                            var currentPercentage = this.getLoaderBar().children('.bar').attr('data-progress');
                            currentPercentage = parseFloat(currentPercentage) + this.onePerCent;
                            
                            this.getLoaderBar().children('.bar').css('width', currentPercentage+'%');
                            this.getLoaderBar().children('.bar').attr('data-progress',currentPercentage);
                            this.getLoaderBar().attr('data-progress',currentPercentage);
                            
                            clearInterval(this.timeOut);
                            
                            this.timeOut = setTimeout(function() {
                                $("#followers-map-progress").hide(300);
                            }, 5000);

                        }

                    }
                }
            },

            geo:{

                coder: {},

                setFollowersLocation: function (twitterIdentification) {

                    var storedData  = amplify.store(twitterIdentification);
                    if(!storedData || !storedData.followers) {
                        return false;
                    }

                    var i = 0;
                    var followersLength = storedData.followers.length;
                    var cluster = tweestand.report.maps.src.followers.cluster;

                    tweestand.report.maps.src.followers.locationLoaderBar.show();

                    $.each(storedData.followers, function(index, follower) {

                        


                        if(!follower.location) {
                            
                            tweestand.report.maps.src.followers.locationLoaderBar.update(index);
                            
                            return true;

                        } else if(follower.place) {

                            var img = '<img src="'+follower.profile_image_url+'">',
                                name = follower.name+'<br/>',
                                location = follower.location;

                            var infowindow = new google.maps.InfoWindow({
                                content: img+name+location,
                                size: new google.maps.Size(50,50)
                            });

                            
                            
                            var coordinates = [];
                            for(var coordinate in follower.place) {
                                coordinates.push(follower.place[coordinate]);
                            }

                            var marker = new google.maps.Marker({
                                title: 'lala',
                                animation: google.maps.Animation.DROP,
                                position: new google.maps.LatLng(coordinates[0], coordinates[1])
                            });
                            
                            cluster.addMarker(marker);

                            google.maps.event.addListener(marker, 'click', function() {
                                infowindow.open(tweestand.report.maps.src.followers.map,marker);
                            });

                            

                            tweestand.report.maps.src.followers.locationLoaderBar.update(index);
                            

                            return true;

                        }else if(follower.place === false){
                            
                            tweestand.report.maps.src.followers.locationLoaderBar.update(index);
                            
                            return true;
                        }

                        setTimeout( function () {

                            

                            tweestand.report.maps.geo.coder.geocode({'address':follower.location}, function(results, status) {
                        
                                switch (status) {
                            
                                    case google.maps.GeocoderStatus.OK:

                                        for (var i in results[0].geometry.location) {
                                            if (results[0].geometry.location.hasOwnProperty(i)){
                                                results[0].geometry.location[i] += Math.random() * (0.00900 -  0.00001) +  0.00001;
                                            }
                                        }

                                        if(results[0].geometry.location) {
                                            follower.place = results[0].geometry.location;
                                        }

                                        var container = $('<div>'),
                                            image = $('<img>'),
                                            name = $('<div>'),
                                            location = $('<div>');
                                            
                                        image.attr('src', follower.profile_image_url);
                                        name.text(follower.screen_name);
                                        location.text(results[0].formatted_address);

                                        container.append(name);
                                        container.append(image);
                                        container.append(location);

                                        var marker = new google.maps.Marker({
                                            title: follower.screen_name,
                                            animation: google.maps.Animation.DROP,
                                            position: results[0].geometry.location,
                                            infowindow: new google.maps.InfoWindow({
                                                content: container.innerHTML
                                            })
                                        });

                                        cluster.addMarker(marker);
   
                                        google.maps.event.addListener(marker, 'click', function() {
                                            this.infowindow.open(cluster.map, this);
                                        });

                                        break;

                                    case google.maps.GeocoderStatus.ZERO_RESULTS:
                                        follower.place = false;
                                        break;
                                    case google.maps.GeocoderStatus.OVER_QUERY_LIMIT:
                                        follower.place = false;
                                        break;
                                    default:
                                        follower.place = false;
                                        
                                }
                                

                                var storedData = amplify.store(twitterIdentification);
                                storedData.followers[index] = follower;
                                amplify.store(twitterIdentification, storedData, {expires: 21600000});
                                tweestand.report.maps.src.followers.locationLoaderBar.update(index);
                                
                            });
                   
                        }, i * 1400);
                        

                
                        i++;
                    });
                }
            }

            
        }
       

    },

    amplify:{

        ajax: {

            decoders: function(){

                amplify.request.decoders.reportDecoder = function(data, status, xhr, success, error) {
                    if (status === 'success' && data !== null) {
                        if (data.status === "success") {
                            success(data);
                        } else if (data.status === "error") {
                            error(data.messages.error);
                        }
                    } else {
                        error("an error occurred while requesting the report, wait a few minutes and try again.");
                    }
                };
            },

            definers: function(){

                amplify.request.define("report", "ajax", {
                    url: "report",
                    dataType: "json",
                    type: "POST",
                    decoder: "reportDecoder"
                });
            }
        }
        
    },

    helpers:{

        perCentOf: function(a, b){
            
            if(b > 100){
                return 100/b;    
            }else{
                var result = (b * a) / 100;
                return result;    
            }
            
        },
        
        getDateRange: function(fromDate, toDate) {

            var dateRange = [];
            while (fromDate <= toDate) {
                dateRange.push(fromDate.toString('yyyy-MM-dd'));
                fromDate = fromDate.add({days: 1});
            }
            return dateRange;

        },

        putCommonError: function(error) {

            var container = $('<div class="alert alert-error"><i class="icon-info-sign"></i> </div>');
            var colse_button = $('<button type="button" class="close" data-dismiss="alert">&times;</button>');
            var message = $('<span></span>');
            container.append(colse_button).append(message);
            container.children('span').text(error.msg);
            if(error.optional){
                container.children('span').after(error.optional);
            }
            $("#messages-list").append(container);

        },

        arrayLast: function(array) {

            Array.prototype.last = function(array){
                return this[this.length - 1];
            };
            
        },

        tableHeaderFixed: function(){
            (function(a){a.fn.fixedHeader=function(c){var b={topOffset:40,bgColor:"#EEEEEE"};if(c){a.extend(b,c)}return this.each(function(){var i=a(this);var j=a(window),d=a("thead.header",i),h=0;var g=d.length&&d.offset().top-b.topOffset;function e(){if(!i.is(":visible")){return}var l,m=j.scrollTop();var k=d.length&&d.offset().top-b.topOffset;if(!h&&g!=k){g=k}if(m>=g&&!h){h=1}else{if(m<=g&&h){h=0}}h?a("thead.header-copy",i).removeClass("hide"):a("thead.header-copy",i).addClass("hide")}j.on("scroll",e);d.on("click",function(){if(!h){setTimeout(function(){j.scrollTop(j.scrollTop()-47)},10)}});d.clone().removeClass("header").addClass("header-copy header-fixed").appendTo(i);var f=[];i.find("thead.header > tr:first > th").each(function(k,l){f.push(a(l).width())});a.each(f,function(l,k){i.find("thead.header > tr > th:eq("+l+"), thead.header-copy > tr > th:eq("+l+")").css({width:k})});i.find("thead.header-copy").css({margin:"0 auto",width:i.width(),"background-color":b.bgColor});e()})}})(jQuery);
        },

        isMobile: function() {
            
            // if(navigator.userAgent.match(/Android|webOS|iPhone|iPod|BlackBerry|iPad/i)){
            //     this.tableHeaderFixed();
            //     this.device.mobile = true;
            // }


            if($(window).width() <= 500){
                this.tableHeaderFixed();
                this.device.mobile = true;
            }

        },

        device: {
            mobile: false
        }

        
    }
};