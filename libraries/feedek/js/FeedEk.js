/*
* FeedEk jQuery RSS/ATOM Feed Plugin v3.0 with YQL API
* http://jquery-plugins.net/FeedEk/FeedEk.html  https://github.com/enginkizil/FeedEk
* Author : Engin KIZIL http://www.enginkizil.com   
*/

(function ($) {
    $.fn.FeedEk = function (opt) {
        var def = $.extend({
            FeedUrl: "http://jquery-plugins.net/rss",
            MaxCount: 5,
            ShowDesc: true,
            ShowPubDate: true,
            DescCharacterLimit: 0,
            TitleLinkTarget: "_blank",
            DateFormat: "",
            DateFormatLang:"en"
        }, opt);

        var id = $(this).attr("id"), i, s = "",dt;
        $("#" + id).empty().append('<img src="loader.gif" />');

        var YQLstr = 'SELECT channel.item FROM feednormalizer WHERE output="rss_2.0" AND url ="' + def.FeedUrl + '" LIMIT ' + def.MaxCount;

        $.ajax({
            url: "https://query.yahooapis.com/v1/public/yql?q=" + encodeURIComponent(YQLstr) + "&format=json&diagnostics=true&callback=?",
            dataType: "json",
            success: function (data) {
                $("#" + id).empty();
                $.each(data.query.results.rss, function (e, itm) {
                    s += '<li class="col-md-4 col-sm-6 post animated fadeInUp">';
                    
                    if (def.ShowPubDate){
                        dt = new Date(itm.channel.item.pubDate);
                        s += '<div class="itemDate entry-date"><h2>';
                        if ($.trim(def.DateFormat).length > 0) {
                            try {
                                moment.lang(def.DateFormatLang);
                                s += moment(dt).format(def.DateFormat);
                            }
                            catch (e){s += dt.toLocaleDateString();}                            
                        }
                        else {
                            s += dt.toLocaleDateString();
                        }
                        s += '</h2></div><div class="entry-cover"><div class="itemTitle entry-header"><a href="' + itm.channel.item.link + '" target="' + def.TitleLinkTarget + '" >' + itm.channel.item.title + '</a></div>';
                    }
                    if (def.ShowDesc) {
                        s += '<div class="itemContent post-item">';
                         if (def.DescCharacterLimit > 0 && itm.channel.item.description.length > def.DescCharacterLimit) {
                            s += itm.channel.item.description.substring(0, def.DescCharacterLimit) + '...';
                        }
                        else {
                            s += itm.channel.item.description;
                         }
                         s += '</div>' + '<a href="' + itm.channel.item.link + '" target="' + def.TitleLinkTarget + '" class="read-more">read more</a></div>';
                    }
                });
                $("#" + id).append('<ul class="feedEkList blog-inner">' + s + '</ul>');
            }
        });
    };
})(jQuery);