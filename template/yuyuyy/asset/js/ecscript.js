var EC = {
  "isSlid": true,
  "Width": $(window).width(),
  "Init": function () {
    EC.Init.LazyLoad = new LazyLoad({});
    EC.Style.Footer();

    $(".gen-search").click(function () {
      $(".pop-1").addClass("show").siblings(".box-bg2").show();
      $("body").css({
        "height": "100%",
        "width": "100%",
        "overflow": "hidden"
      });
    });

    $(".gen-history").click(function () {
      $(".pop-2").addClass("show").siblings(".box-bg2").show();
      EC.Init.LazyLoad.update();
      $("body").css({
        "height": "100%",
        "width": "100%",
        "overflow": "hidden"
      });
    });

    $(".pop-bj").click(function () {
      $(".pop-list-body").removeClass("show").siblings(".box-bg2").hide();
      $("body").css({
        "height": "",
        "width": "",
        "overflow": ""
      });
    });

    $(".pop-2 span").click(function () {
      $(this).addClass("on").siblings().removeClass("on");
      let currentIndex = $(".pop-2 span").index(this);
      let historyElement = $(".history").eq(currentIndex);

      historyElement.fadeIn(800).siblings().hide();
      historyElement.addClass("check").siblings().removeClass("check");

      if (EC.Empty(EC.Cookie.Get("user_id"))) {
        $(".user-history").html(
          "<div class=\"null cor5\">" +
          "<img src=\"" + maccms.path2 + "template/yuyuyy/asset/img/null.png\" alt=\"" + language["2"] + "\">" +
          "<span>" + language["0"] + "</span>" +
          "</div>" +
          "<a href=\"javascript:\" class=\"button top30 head-user\" style=\"width:100%\">" + language["1"] + "</a>"
        );
      } else if ($(".user-history li").length === 0) {
        EC.Ajax(maccms.path + "/index.php/api/history", "post", "json", "", function (response) {
          if (response.code === 1) {
            let historyHtml = "";
            $.each(response.list, function (index, item) {
              historyHtml +=
                "<li>" +
                "<a class=\"history-a flex\" href=\"" + item.data.link + "\" target=\"video\" title=\"" + item.data.name + "\">" +
                "<img class=\"lazy lazy1\" referrerpolicy=\"no-referrer\" alt=\"" + item.data.name + "\" data-src=\"" + item.data.pic + "\"/>" +
                "<div class=\"history-r\">" +
                "<div class=\"hide2\">" + item.data.name + "</div>" +
                "<div><span class=\"cor5\">" + item.data.type.type_name + "</span></div>" +
                "</div>" +
                "</a>" +
                "</li>";
            });
            $(".user-history ul").html(historyHtml);
            EC.Init.LazyLoad.update();
            if (Number($(".lang-bnt").length) === 1) {
              zh_tranBody();
            }
          } else {
            $(".user-history ul").html(EC.History.Msg);
          }
        });
      }
    });

    $(".user-share-button").click(function () {
      EC.Pop.Show($(".user-share-html").html(), language["3"], function () { });
    });

    let loadingElement = $(".gen-loading");
    let loadingTime = loadingElement.data("time");
    setTimeout(function () {
      $(".gen-loading").fadeOut("slow");
    }, loadingTime);

    $(".head-more-a").hover(
      function () {
        $(".nav-more").html("&#xe564;");
        $(".head-more").show();
      },
      function () {
        $(".nav-more").html("&#xe563;");
        $(".head-more").hide();
      }
    );

    let headerOffset = null;
    let headerElement = $(".head");
    let timBoxElement = $(".row-2 .tim-box");
    let timBoxOffset = null;

    if (headerElement.length > 0) {
      headerOffset = headerElement.offset().top;
    }

    if (timBoxElement.length > 0) {
      timBoxOffset = timBoxElement.eq(timBoxElement.length - 1).offset().top;
    }

    $(window).scroll(EC.debounce(function () {
      let scrollTop = $(this).scrollTop();

      headerElement.toggleClass("head-b", scrollTop > headerOffset)
        .css("position", scrollTop > headerOffset ? "fixed" : "");

      $(".head .left, .head .right").toggleClass("head-b", scrollTop > headerOffset);

      if (scrollTop > 300) {
        $(".top").fadeIn(600);
      } else {
        $(".top").fadeOut(600);
      }

      if (scrollTop > timBoxOffset) {
        timBoxElement.eq(timBoxElement.length - 1).css({
          "position": "fixed",
          "top": "100px",
          "width": $(".row-2").width()
        });
      } else {
        timBoxElement.eq(timBoxElement.length - 1).css({
          "position": "",
          "top": "",
          "width": ""
        });
      }
    }, 50));

    $(".top").click(function () {
      $("html,body").animate({ "scrollTop": 0 }, 500);
      headerElement.removeClass("head-b").css({ "position": "" });
    });

    if (Number($(".slid-e").length) === 1) {
      let swiperInstance = new Swiper(".swiper3", {
        "loop": true,
        "slidesPerView": 1,
        "pagination": {
          "el": ".swiper-pagination"
        },
        "on": {
          "slideChangeTransitionStart": function () {
            pauseAllVideos();
            $(".muted").off("click");
            $(".toReplay").off("click");
            $(".slid-e-video").removeClass("v-show");
            $(".slid-e-bj").removeClass("v-hidden");
          },
          "slideChangeTransitionEnd": function () {
            if ($(".wap-hide").is(":hidden") == false && EC.isSlid) {
              EC.isSlid = false;
              setTimeout(function () {
                EC.isSlid = true;
                playCurrentVideo();
              }, 1000);
            }
          }
        }
      });

      function pauseAllVideos() {
        let videoElements = Array.from(document.getElementsByClassName("preview"));
        for (let i = 0; i < videoElements.length; i++) {
          const video = videoElements[i];
          video.pause();
          video.currentTime = 0;
        }
      }

      let muteState = 0;

      function playCurrentVideo() {
        let currentVideo = document.querySelector(".slid-e").querySelector(".swiper-slide-active").querySelector("video");
        let currentSlide = $(".slid-e .swiper-slide-active");

        if (~~currentVideo.duration > 10) {
          currentSlide.find(".slid-e-video").addClass("v-show");
          currentSlide.find(".slid-e-bj").addClass("v-hidden");

          if (currentVideo.muted) {
            $(".muted .fa").removeClass("ds-shengyin").addClass("ds-liulan");
          } else {
            $(".muted .fa").removeClass("ds-liulan").addClass("ds-shengyin");
          }

          $(".muted").click(function () {
            if (currentVideo.muted) {
              $(".muted .fa").removeClass("ds-liulan").addClass("ds-shengyin");
              currentVideo.muted = false;
              muteState = 1;
            } else {
              $(".muted .fa").removeClass("ds-shengyin").addClass("ds-liulan");
              currentVideo.muted = true;
              muteState = 0;
            }
          });

          if (muteState === 1) {
            $(".muted .fa").removeClass("ds-liulan").addClass("ds-shengyin");
            currentVideo.muted = false;
          }

          $(".toReplay").click(function () {
            currentSlide.find(".slid-e-video").addClass("v-show");
            currentSlide.find(".slid-e-bj").addClass("v-hidden");
            currentVideo.currentTime = 0;
            currentVideo.play();
          });

          currentVideo.play();

          currentVideo.addEventListener("ended", function () {
            currentSlide.find(".slid-e-video").removeClass("v-show");
            currentSlide.find(".slid-e-bj").removeClass("v-hidden");
            swiperInstance.slideNext();
          });
        } else {
          setTimeout(function () {
            swiperInstance.slideNext();
          }, 6000);
        }
      }
    }

    let heightLimitElement = $("#height_limit");
    if (heightLimitElement.height() >= 80) {
      heightLimitElement.addClass("occlusion");
      $(".text-open").show();
    }

    $(".tim-bnt").click(function () {
      if (heightLimitElement.hasClass("height_rel")) {
        $(".tim-bnt").html("<i class=\"fa r6 ease\">&#xe563;</i>" + language["4"]);
        heightLimitElement.removeClass("height_rel").addClass("occlusion");
      } else {
        $(".tim-bnt").html("<i class=\"fa r6 ease\">&#xe564;</i>" + language["4"]);
        heightLimitElement.addClass("height_rel").removeClass("occlusion");
      }
    });

    EC.Swiper.Navs();
    EC.Swiper.Slide();
    EC.Swiper.Roll();
    EC.Actor.Detail();

    $(".gen-left-list").click(function () {
      let moreContent = $(".head-more").html();
      let boldClass = "bold0";
      let showClass = "wap-show1";
      let navElement = $(".head-nav");

      if (navElement.hasClass("bold1")) {
        boldClass = "bold1";
      }
      if (navElement.hasClass("wap-show0")) {
        showClass = "wap-show0";
      }

      EC.Pop.Drawer(
        "<div class='drawer-nav drawer-scroll " + boldClass + " " + showClass + "'>" +
        "<div class='drawer-scroll-list'>" + moreContent + "</div>" +
        "</div>",
        function () {
          let accountMenu = $(".gen-account-menu").html();
          $(".drawer-list-box").prepend("<div class='drawer-menu cor2'>" + accountMenu + "</div>");
        }
      );
    });

    $(".playBut").click(function () {
      let videoUrl = $(this).attr("data-url");
      $(".play-advance .topfadeInUp").html(
        "<video id=\"xkPlayer\" width=\"100%\" height=\"100%\" controls preload=\"auto\" autoplay>" +
        "<source src=\"" + videoUrl + "\" type=\"video/mp4\">" +
        "</video>"
      );
      $(".play-advance").show();
    });

    $(".play-advance .box-bg,.play-advance .mfp-close").click(function () {
      let player = document.getElementById("xkPlayer");
      player.currentTime = 0;
      player.pause();
      $(".play-advance").hide();
      $(".mfp-iframe").remove();
    });

    $(".deployment").click(function () {
      let parameterInfo = $(".info-parameter").html();
      EC.Pop.Drawer(parameterInfo, function () {
        $(".drawer-list-box").addClass("drawer-list-b bj3");
        $(document).on("click", ".drawer-of", function () {
          EC.Pop.DrawerDel();
        });
      });
    });

    $(".wap-diy-vod-e .vod-link").hover(
      function () {
        $(this).addClass("box");
        $(this).children(".vod-no-dom-show").hide();
        $(this).children(".vod-no-dom").show();
      },
      function () {
        $(this).removeClass("box");
        $(this).children(".vod-no-dom-show").show();
        $(this).children(".vod-no-dom").hide();
      }
    );

    $("#BuyPopEdom").click(function () {
      let buyButton = $(this);
      if (buyButton.attr("data-id")) {
        if (confirm(language["6"])) {
          EC.Ajax(
            maccms.path + "/index.php/user/ajax_buy_popedom.html?id=" +
            buyButton.attr("data-id") + "&mid=" + buyButton.attr("data-mid") +
            "&sid=" + buyButton.attr("data-sid") + "&nid=" + buyButton.attr("data-nid") +
            "&type=" + buyButton.attr("data-type"),
            "get", "json", "",
            function (response) {
              buyButton.addClass("disabled");
              _EC.Pop.Msg(response.msg);
              if (Number(response.code) === 1) {
                top.location.reload();
              }
              buyButton.removeClass("disabled");
            }
          );
        }
      }
    });

    $("#check").click(function () {
      let checkButton = $(this);
      if (checkButton.attr("data-id")) {
        EC.Ajax(
          maccms.path + "/index.php/ajax/pwd.html?id=" + checkButton.attr("data-id") +
          "&mid=" + checkButton.attr("data-mid") + "&type=" + checkButton.attr("data-type") +
          "&pwd=" + checkButton.parents("form").find("input[name=\"pwd\"]").val(),
          "get", "json", "",
          function (response) {
            checkButton.addClass("disabled");
            _EC.Pop.Msg(response.msg);
            if (Number(response.code) === 1) {
              location.reload();
            }
            checkButton.removeClass("disabled");
          }
        );
      }
    });

    switch (maccms.aid) {
      case "12":
      case "11":
        let dataList = $("#dataList");
        if (dataList.length > 0) {
          let dataConfig = dataList.data();
          let filterParams = {
            "type": dataConfig.type,
            "class": dataConfig.class,
            "area": dataConfig.area,
            "lang": dataConfig.lang,
            "version": dataConfig.version,
            "state": dataConfig.state,
            "letter": dataConfig.letter
          };

          $(".ec-casc-list .swiper-slide").click(function () {
            $(this).addClass("nav-dt").siblings().removeClass("nav-dt");
            EC.Swiper.Navs();
            filterParams[$(this).data("type")] = $(this).data("val");

            if (EC.Empty($(this).data("type"))) return;

            dataList.html("");
            HTML.Skeleton(dataList, 3, dataConfig.tpl);
            EC.flow.get(filterParams, dataList, dataConfig, function () { });
          });

          $(".site-tabs a").click(function () {
            $(this).addClass("active").siblings().removeClass("active");

            if (Number($("#dataList .null").length) === 1) return;

            filterParams.by = $(this).data("type");
            dataList.html("");
            HTML.Skeleton(dataList, 3, dataConfig.tpl);
            EC.flow.get(filterParams, dataList, dataConfig, function () { });
          });

          HTML.Skeleton(dataList, 3, dataConfig.tpl);
          filterParams[$(this).data("type")] = $(this).data("val");
          EC.flow.get(filterParams, dataList, dataConfig, function () { });
        }
        break;

      case "14":
      case "104":
      case "15":
        $(".anthology-tab a").click(function () {
          $(this).addClass("on nav-dt").siblings().removeClass("on nav-dt");
          let tabIndex = $(".anthology-tab a").index(this);
          let anthologyList = $(".anthology-list .none").eq(tabIndex);
          anthologyList.fadeIn(800).siblings().hide();
          anthologyList.addClass("dx").siblings().removeClass("dx");
          EC.Swiper.Navs();
        });

        $("#zxdaoxu").each(function () {
          $(this).on("click", function (event) {
            event.preventDefault();
            $(".dx").each(function () {
              let listItems = $(this).find("li");
              for (let startIndex = 0, endIndex = listItems.length - 1; startIndex < endIndex;) {
                let startItem = listItems.eq(startIndex).clone(true);
                let endItem = listItems.eq(endIndex).replaceWith(startItem);
                listItems.eq(startIndex).replaceWith(endItem);
                ++startIndex;
                --endIndex;
              }
            });
          });
        });

        $("#role .public-list-box").click(function () {
          let roleIndex = $(this).index();
          let roleType = $("#role .cor5").eq(roleIndex).text();
          let roleTitle = $("#role .time-title").eq(roleIndex).text();
          let roleData = $("#role .lazy").eq(roleIndex).data();
          let roleDescription = roleData.text;

          if (roleDescription.length < 1) {
            roleDescription = language["7"];
          }

          let roleCardHtml =
            "<div class=\"role-card\">" +
            "<div class=\"card-top flex\">" +
            "<div class=\"left\">" +
            "<img class=\"lazy\" src=\"" + roleData.src + "\" alt=\"" + roleTitle + "\">" +
            "</div>" +
            "<div class=\"right\">" +
            "<p>" + roleTitle + "</p>" +
            "<p class=\"cor5\">" + roleType + "</p>" +
            "</div>" +
            "</div>" +
            "<div class=\"card-bottom\">" +
            "<p class=\"card-title\">" + language["8"] + "</p>" +
            "<div class=\"card-text cor5\">" + roleDescription + "</div>" +
            "</div>" +
            "</div>";

          EC.Pop.Show(roleCardHtml, language["9"], function () { });
        });

        $(".vod-detail .vod-detail-bnt .button").click(function () {
          var firstPlayLink = $(".anthology-list-play a").eq(0).attr("href");
          if (typeof firstPlayLink !== 'undefined' && firstPlayLink) {
              location.href = firstPlayLink;
          } 
        });

        $(".player-button-ac").click(function () {
          $(".anthology-list").toggleClass("player-list-ac");
        });

        $(".play-tips-bnt").click(function () {
          $(".tips-box").slideToggle();
          $(".charge,.player-share-box").hide();
        });

        $(".ec-report").click(function () {
          let reportData = $(this).data();
          EC.Gbook.Report(reportData);
        });

        $(".charge-button").click(function () {
          $(".charge").fadeToggle();
          $(".player-share-box,.tips-box").hide();
        });

        if ($(".comment-form").length < 1) {
          EC.Comment.Login = $(this).data().login;
          EC.Comment.Verify = $(this).data().verify;
          EC.Comment.Init();
          EC.Comment.Show(1);
        }

        $("#expand_details").click(function () {
          $(".player-vod-box").hide();
          $(".player-list-box").hide();
          $(".player-details-box").show();
        });

        $(".player-close").click(function () {
          $(".player-vod-box").show();
          $(".player-list-box").show();
          $(".player-details-box").hide();
          $(".player-return .none").hide();
          $(".player-vod-no1").show();
          $(".player-vod-no2").html(
            "<div class=\"top40\">" +
            "<div class=\"loading\">" +
            "<span></span><span></span><span></span><span></span><span></span>" +
            "</div>" +
            "</div>"
          ).hide();
        });

        $(".player-vod-no1 .public-list-box").click(function () {
          $(".player-return .none").show();
          $(".player-vod-no1").hide();
          $(".player-vod-no2").show();

          EC.Ajax(
            maccms.path + "/index.php/api/actor_vod_player_api?id=" + $(this).attr("data-id"),
            "get", "json", "",
            function (response) {
              if (Number(response.code) === 1) {
                let vodListHtml = "";
                $.each(response.list, function (index, item) {
                  vodListHtml +=
                    "<div class=\"public-list-box public-pic-b\">" +
                    "<div class=\"public-list-div\">" +
                    "<a class=\"public-list-exp\" href=\"" + item.url + "\" title=\"" + item.name + "\">" +
                    "<img class=\"lazy lazy1 gen-movie-img " + maccms.vod_mask + "\" referrerpolicy=\"no-referrer\" alt=\"" + language["10"] + "\" data-src=\"" + item.pic + "\" />" +
                    "</a>" +
                    "<i class=\"collection fa\" data-type=\"2\" data-mid=\"" + maccms.mid + "\" data-id=\"" + item.id + "\">&#xe577;</i>" +
                    "</div>" +
                    "<div class=\"public-list-button\">" +
                    "<a target=\"_blank\" class=\"time-title hide ft4\" href=\"" + item.url + "\" title=\"" + item.name + "\">" + item.name + "</a>" +
                    "</div>" +
                    "</div>";
                });

                $(".player-vod-no2").html(
                  "<div class=\"role-card top20\">" + response.html + "</div>" +
                  "<div class=\"title-m cor4\"><h5>" + language["11"] + "</h5></div>" +
                  "<div class=\"flex wrap border-box hide-b-16 wap-diy-vod-a\">" + vodListHtml + "</div>"
                );

                EC.Init.LazyLoad.update();
              } else {
                _EC.Pop.Msg(language["12"], "", "error");
              }
            }
          );
        });

        $(".player-return .none").click(function () {
          $(this).hide();
          $(".player-vod-no1").show();
          $(".player-vod-no2").html(
            "<div class=\"top40\">" +
            "<div class=\"loading\">" +
            "<span></span><span></span><span></span><span></span><span></span>" +
            "</div>" +
            "</div>"
          ).hide();
        });

        break;

      case "4":
        EC.Gbook.Init();
        break;

      case "24":
        $(".tim-content img").touchTouch();
        let commentElement = $(".ds-comment");
        if (Number(commentElement.length) === 1) {
          EC.Comment.Login = commentElement.data().login;
          EC.Comment.Verify = commentElement.data().verify;
          EC.Comment.Init();
          EC.Comment.Show(1);
        }
        break;

      case "21":
        let newsDataList = $("#dataList");
        if (newsDataList.length > 0) {
          let newsConfig = newsDataList.data();
          HTML.Skeleton(newsDataList, 3, newsConfig.tpl);
          let newsParams = { "type": newsConfig.type };
          EC.flow.get(newsParams, newsDataList, newsConfig, function () {
            EC.Swiper.Roll();
          });
        }
        break;

      case "23":
        let searchDataList = $("#dataList");
        if (searchDataList.length > 0) {
          let searchConfig = searchDataList.data();
          HTML.Skeleton(searchDataList, 3, searchConfig.tpl);
          let searchParams = {
            "wd": searchConfig.type,
            "tag": searchConfig.wdtag
          };
          EC.flow.get(searchParams, searchDataList, searchConfig, function () {
            EC.Swiper.Roll();
          });
        }
        break;

      case "84":
        $(".art-so-tag").click(function () {
          let tagDataList = $("#dataList");
          let tagConfig = tagDataList.data();
          tagDataList.html("");
          HTML.Skeleton(tagDataList, 3, tagConfig.tpl);
          let tagParams = {
            "wd": tagConfig.type,
            "tag": tagConfig.wdtag
          };
          EC.flow.get(tagParams, tagDataList, tagConfig, function () {
            EC.Swiper.Roll();
          });
        });
        break;

      case "82":
        let actorDataList = $("#dataList");
        if (actorDataList.length > 0) {
          let actorConfig = actorDataList.data();
          let actorParams = { "type": actorConfig.type };

          $(".ec-casc-list .swiper-slide").click(function () {
            $(this).addClass("nav-dt").siblings().removeClass("nav-dt");
            EC.Swiper.Navs();
            actorParams[$(this).data("type")] = $(this).data("val");
            actorDataList.html("");
            HTML.Skeleton(actorDataList, 3, actorConfig.tpl);
            EC.flow.get(actorParams, actorDataList, actorConfig, function () { });
          });

          HTML.Skeleton(actorDataList, 3, actorConfig.tpl);
          actorParams[$(this).data("type")] = $(this).data("val");
          EC.flow.get(actorParams, actorDataList, actorConfig, function () { });
        }
        break;

      case "302":
        let topicDataList = $("#dataList");
        if (topicDataList.length > 0) {
          let topicConfig = topicDataList.data();
          let topicParams = {};

          $(".ec-casc-list .swiper-slide").click(function () {
            $(".swiper-slide").removeClass("nav-dt");
            $(this).addClass("nav-dt");
            EC.Swiper.Navs();
            topicParams.type = $(this).data("id");
            topicDataList.html("");
            HTML.Skeleton(topicDataList, 3, topicConfig.tpl);
            EC.flow.get(topicParams, topicDataList, topicConfig, function () { });
          });

          HTML.Skeleton(topicDataList, 3, topicConfig.tpl);
          topicParams[$(this).data("type")] = $(this).data("val");
          EC.flow.get(topicParams, topicDataList, topicConfig, function () { });
        }
        break;
    }

    if (Number(maccms.mid) === 11) {
      $(".web-so-btn").click(function () {
        let searchValue = $(".website-val").val();
        if (searchValue) {
          window.open($(".website-filter-grid .action").attr("data-url") + searchValue);
        } else {
          _EC.Pop.Msg(language["14"], "", "error");
        }
      });

      $(".website-filter-grid .icon").click(function () {
        $(".website-filter-grid .action").removeClass("action");
        $(this).addClass("action");
      });

      $(".togo").click(function () {
        let websiteId = $(this).attr("data-id");
        let websiteStatus = $(this).attr("data-mi");

        if (Number(websiteStatus) === 1) {
          _EC.Pop.Msg(language["15"], "", "error");
        } else {
          EC.Ajax(
            maccms.path + "/index.php/api/website?id=" + websiteId,
            "get", "json", "",
            function (response) {
              if (response.smg === 1) {
                EC.Pop.Show(
                  "<div class=\"website-title\">" + response.data.website_name + "</div>" +
                  "<div class=\"card-text cor5\">" +
                  "<p>" + response.data.website_blurb + "</p>" +
                  "<p>" + response.data.website_content + "</p>" +
                  "</div>" +
                  "<div class=\"flex website-tag top20\">" + response.data.website_tag + "</div>",
                  language["20"],
                  function () { }
                );
              } else {
                _EC.Pop.Msg(language["15"], "", "error");
              }
            }
          );
        }
      });

      $("#tou_gao").click(function () {
        if (Number(EC.User.IsLogin) === 0) {
          EC.User.Login();
          return;
        }

        EC.Pop.Show(
          "<form class=\"tg-form\">" +
          "<p class=\"cor5 top10\">" + language["16"] + "</p>" +
          "<div class=\"region nav-link textarea border\">" +
          "<textarea class=\"tg-content cor5\" name=\"gbook_content\" style=\"width:100%;height:100%\"></textarea>" +
          "</div>" +
          "<div class=\"flex\">" +
          "<input type=\"text\" class=\"input bj br cor5\" name=\"verify\" value=\"\" maxlength=\"4\" size=\"20\">" +
          "<img class=\"ds-verify-img\" src=\"/index.php/verify/index.html\" alt=\"" + language["19"] + "\" onclick=\"this.src = this.src+'?'\">" +
          "</div>" +
          "<input type=\"button\" class=\"tg-submit button top20 submit_btn\" style=\"width:100%\" value=\"" + language["17"] + "\">" +
          "</form>",
          language["18"],
          function () {
            $(".tg-submit").click(function () {
              EC.Gbook.Tg();
            });
          }
        );
      });
    }

    $("#website_user").click(function () {
      $.ajax({
        "url": window.location.href + "&pdw=" + $("#website_password").val(),
        "type": "post",
        "dataType": "json",
        "success": function (response) {
          if (Number(response.smg) === 1) {
            window.location.replace(response.url);
          } else {
            window.location.replace("https://www.kugou.com/song/#hash=8C1A6044DDF1650A82B42769C47FD645&album_id=501600");
          }
        }
      });
    });

    $(".jp-download").click(function () {
      let downloadContent =
        "<p class=\"cor5 top10\">" + language["21"] + "</p>" +
        "<div class=\"region nav-link textarea bj\">" +
        "<textarea id=\"bar2\" class=\"cor5\" style=\"width:100%;height:100%\">" +
        language["22"] + "《" + ecData.list[ecData.playid].name + "》" + language["23"] + ecData.list[ecData.playid].url +
        "</textarea>" +
        "</div>" +
        "<button type=\"button\" class=\"button copyBtn\" style=\"width:100%\" data-clipboard-action=\"copy\" data-clipboard-target=\"#bar2\">" +
        language["24"] +
        "</button>";

      EC.Pop.Show(downloadContent, language["25"], function () {
        $(document).on("click", ".copyBtn", function () {
          _EC.Pop.Msg(language["26"], "", "success");
          $(".window").remove();
        });
      });
    });

    // 弹窗已禁用
    // if ($(".ds-pop").length > 0 && EC.Empty(EC.Cookie.Get("ecPopup"))) {
    //   EC.Cookie.Set("ecPopup", 1, 1);
    //   $("#notice").show();
    //   $("#notice .box-bg,#notice .button").click(function () {
    //     $("#notice").hide();
    //   });
    // }

    $(".player-switch").click(function () {
      let playerElement = $(".player");
      if (playerElement.hasClass("player-switch-box")) {
        $(this).html("&#xe565;");
        playerElement.removeClass("player-switch-box");
      } else {
        $(this).html("&#xe566;");
        playerElement.addClass("player-switch-box");
      }
    });

    const themeMessage = " %c 主题™ %c 严禁利用主题建立违法网站";
    console.log(
      "\n" + themeMessage + "\n",
      "color: #fadfa3; background: #030307; padding:5px 0; font-size:18px;",
      "background: #fadfa3; padding:5px 0; font-size:18px;"
    );

    if ($(".week-title").length > 0) {
      let weekDays = ["一", "二", "三", "四", "五", "六", "日"];
      let currentDay = new Date().getDay();

      if (currentDay === 0) {
        currentDay = 7;
      }

      let currentWeekList = $("#week-list" + currentDay);
      let currentWeekContent = currentWeekList.children();

      currentWeekList.show();
      $(".week-bj").addClass("week-" + currentDay);
      $(".week-key" + currentDay).addClass("tim");

      let weekDataList = $("#dataList");
      let weekConfig = weekDataList.data();
      let weekParams = {
        "weekday": weekDays[currentDay - 1],
        "num": weekConfig.num,
        "by": weekConfig.by,
        "type": weekConfig.type
      };

      HTML.Skeleton(currentWeekContent, 3, "vod");
      loadWeekData(weekParams, currentWeekContent, weekConfig);

      $(".week-title .week-select a").click(function () {
        $(this).addClass("tim").siblings().removeClass("tim");
        let weekData = $(this).data();

        $(".week-bj").removeClass("week-1 week-2 week-3 week-4 week-5 week-6 week-7")
          .addClass("week-" + weekData.index);

        $(".animated").hide();
        currentWeekList = $("#week-list" + weekData.index);
        currentWeekList.show();
        currentWeekContent = currentWeekList.children();

        if (currentWeekContent.html().length > 50) return;

        weekParams.weekday = weekData.val;
        HTML.Skeleton(currentWeekContent, 3, "vod");
        loadWeekData(weekParams, currentWeekContent, weekConfig);
      });
    }

    function loadWeekData(params, contentElement, config) {
      setTimeout(function () {
        let htmlArray = [];
        let currentTime = Math.round(new Date() / 1000);
        let requestParams = params;
        requestParams.time = currentTime;
        requestParams.key = EC.Md5(currentTime);

        EC.Ajax(config.api, "post", "json", requestParams, function (response) {
          if (Number(response.code) === 1) {
            htmlArray = HTML.Art(response, config);

            if (EC.flow.empty(response.list.length, contentElement)) return;

            contentElement.html(htmlArray.join(""));
            EC.Init.LazyLoad.update();
            EC.Style.Footer();

            if ($(".lang-bnt").length === 1) {
              zh_tranBody();
            }
          } else {
            if (Number(response.code) === 2) {
              $(".flow-more").html(response.msg);
            } else {
              _EC.Pop.Msg(language["27"] + response.msg, "", "error");
            }
          }
        }, function () {
          _EC.Pop.Msg(language["28"], "", "error");
        });
      }, 100);
    }
  },
  "Style": {
    "Init": function () {
      let themeStyleElement = $(".theme-style");
      let savedStyle = EC.Cookie.Get("ec-wap_style");

      if (!EC.Empty(savedStyle)) {
        $("body").attr("class", savedStyle);
        if (savedStyle === "theme1") {
          themeStyleElement.html("&#xe574;").attr("data-id", 1);
        } else {
          themeStyleElement.html("&#xe575;").attr("data-id", 2);
        }
      }

      themeStyleElement.click(function () {
        EC.Style.Switch(themeStyleElement);
      });
    },

    "Set": function (styleName) {
      EC.Cookie.Set("ec-wap_style", styleName);
    },

    "Switch": function (styleElement) {
      let currentThemeId = styleElement.attr("data-id");

      if (Number(currentThemeId) === 1) {
        $("body").attr("class", "theme2");
        styleElement.html("&#xe575;").attr("data-id", 2);
        EC.Style.Set("theme2");
      } else {
        $("body").attr("class", "theme1");
        styleElement.html("&#xe574;").attr("data-id", 1);
        EC.Style.Set("theme1");
      }
    },

    "Footer": function () {
      if ($(document.body).height() < $(window).height()) {
        $(".footer").addClass("footerLess");
      } else {
        $(".footer").removeClass("footerLess");
      }
    }
  },

  "Cookie": {
    "Set": function (cookieName, cookieValue, expirationDays) {
      let expirationDate = new Date();
      expirationDate.setTime(expirationDate.getTime() + expirationDays * 24 * 60 * 60 * 1000);
      document.cookie = cookieName + "=" + encodeURIComponent(cookieValue) +
        ";path=/;expires=" + expirationDate.toUTCString();
    },

    "Get": function (cookieName) {
      let cookieMatch = document.cookie.match(new RegExp("(^| )" + cookieName + "=([^;]*)(;|$)"));
      if (cookieMatch != null) {
        return decodeURIComponent(cookieMatch[2]);
      }
    },

    "Del": function (cookieName) {
      let pastDate = new Date();
      pastDate.setTime(pastDate.getTime() - 1);
      let cookieValue = this.Get(cookieName);

      if (cookieValue != null) {
        document.cookie = cookieName + "=" + encodeURIComponent(cookieValue) +
          ";path=/;expires=" + pastDate.toUTCString();
      }
    }
  },

  "Empty": function (value) {
    return value == null || value === "";
  },

  "debounce": function (func, delay) {
    let timeoutId = null;
    return function () {
      let context = this;
      let args = arguments;
      clearTimeout(timeoutId);
      timeoutId = setTimeout(function () {
        func.apply(context, args);
      }, delay);
    };
  },

  "GoBack": function () {
    let currentDomain = document.domain;
    if (document.referrer.indexOf(currentDomain) > 0) {
      history.back();
    } else {
      window.location = "//" + currentDomain;
    }
  },

  "Ajax": function (url, method, dataType, data, successCallback, errorCallback, completeCallback) {
    method = method || "get";
    dataType = dataType || "json";
    data = data || "";
    errorCallback = errorCallback || "";
    completeCallback = completeCallback || "";

    $.ajax({
      "url": url,
      "type": method,
      "dataType": dataType,
      "data": data,
      "timeout": 5000,
      "beforeSend": function (xhr) { },
      "error": function (xhr, status, error) {
        if (errorCallback) {
          errorCallback(xhr, status, error);
        }
      },
      "success": function (response) {
        successCallback(response);
      },
      "complete": function (xhr, status) {
        if (completeCallback) {
          completeCallback(xhr, status);
        }
      }
    });
  },

  "flow": {
    "load": function (options) {
      options = options || {};
      let flowInstance = this;
      let currentPage = 0;
      let isLoading;
      let isFinished;
      let loadTimeout;
      let containerElement = $(options.elem);

      if (!containerElement[0]) return;

      let scrollElement = $(options.scrollElem || document);
      let marginBottom = options.mb || 50;
      let isAutoLoad = "isAuto" in options ? options.isAuto : true;
      let endMessage = options.end || language["29"];
      let isCustomScrollElem = options.scrollElem && options.scrollElem !== document;
      let loadMoreText = "<i class=\"fa ds-jiantouyou\"></i>" + language["30"];
      let loadMoreElement = $("<div class=\"flow-more cor5\"><a href=\"javascript:;\">" + loadMoreText + "</a></div>");

      if (!containerElement.find(".flow-more")[0]) {
        containerElement.append(loadMoreElement);
      }

      let renderContent = function (content, isLastPage) {
        if (Number(currentPage) === 1) {
          $(".flow-more").siblings().remove();
        }

        content = $(content);
        loadMoreElement.before(content);
        isLastPage = Number(isLastPage) === 0 ? true : null;

        if (isLastPage) {
          loadMoreElement.html(endMessage);
        } else {
          loadMoreElement.find("a").html(loadMoreText);
        }

        isFinished = isLastPage;
        isLoading = null;
        EC.Init.LazyLoad.update();
      };

      let loadNextPage = function () {
        isLoading = true;
        loadMoreElement.find("a").html("<i class=\"loading3\"></i>" + language["31"]);

        if (typeof options.done === "function") {
          options.done(++currentPage, renderContent, containerElement);
        }
      };

      loadNextPage();

      loadMoreElement.find("a").on("click", function () {
        if (isFinished) return;
        if (!isLoading) {
          loadNextPage();
        }
      });

      if (!isAutoLoad) return flowInstance;

      scrollElement.off("scroll");
      scrollElement.on("scroll", function () {
        let scrollContainer = $(this);
        let scrollTop = scrollContainer.scrollTop();

        if (loadTimeout) {
          clearTimeout(loadTimeout);
        }

        if (isFinished || !containerElement.width()) return;

        loadTimeout = setTimeout(function () {
          let containerHeight = isCustomScrollElem ? scrollContainer.height() : $(window).height();
          let totalHeight = isCustomScrollElem ? scrollContainer.prop("scrollHeight") : document.documentElement.scrollHeight;

          if (totalHeight - scrollTop - containerHeight <= marginBottom) {
            if (!isLoading) {
              loadNextPage();
            }
          }
        }, 100);
      });

      return flowInstance;
    },

    "empty": function (listLength, containerElement) {
      if (Number(listLength) === 0) {
        containerElement.html(
          "<div class=\"null cor5\">" +
          "<img src=\"" + maccms.path2 + "template/yuyuyy/asset/img/null.png\" alt=\"" + language["2"] + "\">" +
          "<span>" + language["32"] + "</span>" +
          "</div>"
        );
        return true;
      }
      return false;
    },

    "get": function (requestParams, dataListElement, config, callback) {
      if (dataListElement.length > 0) {
        EC.flow.load({
          "elem": "#dataList",
          "isAuto": config.pattern,
          "end": config.txt,
          "done": function (pageNumber, renderCallback, containerElement) {
            setTimeout(function () {
              let htmlArray = [];
              let currentTimestamp = Math.ceil(new Date().getTime() / 1000);
              let ajaxParams = $.extend(requestParams, {
                "page": pageNumber,
                "time": currentTimestamp,
                "key": EC.Md5(currentTimestamp)
              });

              EC.Ajax(config.api, "post", "json", ajaxParams, function (response) {
                if (Number(response.code) === 1) {
                  if (EC.flow.empty(response.list.length, containerElement)) return;

                  htmlArray = HTML.Art(response, config);
                  renderCallback(htmlArray.join(""), pageNumber < response.pagecount);
                  callback();
                  EC.Style.Footer();

                  if ($(".lang-bnt").length === 1) {
                    zh_tranBody();
                  }
                } else {
                  if (Number(response.code) === 2) {
                    $(".flow-more").html(response.msg);
                  } else {
                    _EC.Pop.Msg(language["27"] + response.msg, "", "error");
                  }
                }
              }, function () {
                _EC.Pop.Msg(language["28"], "", "error");
              });
            }, 100);
          }
        });
      }
    }
  },
  "Copy": {
    "Init": function () {
      if (EC.Width < 767) {
        // Mobile view handlers
        $(".play-score").click(function () {
          EC.Pop.Show($("#rating").prop("outerHTML"), language["33"], function () { });
        });

        $(".vod-detail-share").click(function () {
          $(".vod-detail .share-box").remove();
          let shareContent =
            "<div class=\"cor5 radius\">" +
            "<span class=\"share-tips\">" + language["34"] + "</span>" +
            "<span id=\"bar\" class=\"share-url bj cor5\">" + window.location.href + $(document).attr("title") + "</span>" +
            "<button type=\"button\" class=\"share-copy bj2 ho radius copyBtn\" data-clipboard-action=\"copy\" data-clipboard-target=\"#bar\">" + language["35"] + "</button>" +
            "</div>";

          EC.Pop.Show(shareContent, language["18"], function () {
            $(document).on("click", ".copyBtn", function () {
              _EC.Pop.Msg(language["36"], "", "success");
              $(".window").remove();
            });
          });

          new ClipboardJS(".copyBtn");
        });
      } else {
        // Desktop view handlers
        $(".share-url").html(window.location.href + $(document).attr("title"));
        new ClipboardJS(".copyBtn");
        EC.Copy.Qrcode();

        $(".vod-detail-share").hover(
          function () {
            $(".vod-detail .share-box").show();
            $(document).on("click", ".copyBtn", function () {
              $(".vod-detail .share-box").hide();
              _EC.Pop.Msg(language["36"], "", "success");
            });
          },
          function () {
            $(".vod-detail .share-box").hide();
          }
        );
      }

      // Player share button handler
      $(".player-share-button").click(function () {
        $(".player-share-box").fadeToggle();
        $(".charge,.tips-box").hide();

        $(".player-share-box .copyBtn").click(function () {
          _EC.Pop.Msg(language["36"], "", "success");
          $(".player-share-box").slideUp();
        });
      });

      // URL copy functionality
      let urlCopyInstance = new ClipboardJS(".CopyUel");

      $(".CopyUel").click(function () {
        urlCopyInstance.on("success", function () {
          _EC.Pop.Msg(language["37"], "", "success");
        });

        urlCopyInstance.on("error", function () {
          _EC.Pop.Msg(language["38"], "", "error");
        });
      });
    },

    "Qrcode": function () {
      $(".hl-cans").each(function () {
        if ($(this).length) {
          $(this).qrcode({
            "width": 120,
            "height": 120,
            "text": encodeURI(window.location.href)
          });

          function convertCanvasToImage(canvas) {
            let imageElement = new Image();
            imageElement.src = canvas.toDataURL("image/png");
            return imageElement;
          }

          let canvasElement = $("canvas")[0];
          let qrCodeImage = convertCanvasToImage(canvasElement);
          $(this).next(".share-pic").append(qrCodeImage);
        }
      });
    }
  }
  ,
  "Swiper": {
    "Navs": function () {
      new Swiper(".nav-swiper", {
        "observer": true,
        "freeMode": true,
        "slidesPerView": "auto",
        "direction": "horizontal",
        "on": {
          "init": function () {
            EC.Swiper.Nav(this.$el, this.$wrapperEl, this.virtualSize);
          },
          "observerUpdate": function () {
            EC.Swiper.Nav(this.$el, this.$wrapperEl, this.virtualSize);
          }
        }
      });
    },

    "Nav": function (swiperElement, wrapperElement, virtualSize) {
      if (swiperElement.find(".nav-dt").length > 0) {
        let activeItemWidth = swiperElement.find(".nav-dt").outerWidth(true);
        let activeItemOffset = swiperElement.find(".nav-dt")[0].offsetLeft;
        let containerWidth = wrapperElement.parent().outerWidth(true);
        let totalVirtualSize = parseInt(virtualSize);

        wrapperElement.transition(300);

        if (activeItemOffset < (containerWidth - parseInt(activeItemWidth)) / 2) {
          wrapperElement.transform("translate3d(0px,0px,0px)");
        } else {
          if (activeItemOffset > totalVirtualSize - (parseInt(activeItemWidth) + containerWidth) / 2) {
            wrapperElement.transform("translate3d(" + (containerWidth - totalVirtualSize) + "px,0px,0px)");
          } else {
            wrapperElement.transform("translate3d(" + ((containerWidth - parseInt(activeItemWidth)) / 2 - activeItemOffset) + "px,0px,0px)");
          }
        }
      }
    },

    "Slide": function () {
      new Swiper(".slide-swiper", {
        "autoplay": true,
        "loop": true,
        "slidesPerView": 1
      });

      new Swiper(".mySwiper", {
        "loop": true,
        "autoplay": true,
        "clickable": true,
        "slidesPerView": 1,
        "pagination": {
          "el": ".swiper-pagination"
        }
      });

      new Swiper(".slide-swiper2", {
        "autoplay": {
          "disableOnInteraction": false
        },
        "loop": true,
        "slidesPerView": 1,
        "on": {
          "init": function () {
            let initialBackgroundImage = $(".lazy-p").eq(1).css("backgroundImage");
            $(".slide-time-ios").css("backgroundImage", initialBackgroundImage);
          },
          "slideChangeTransitionEnd": function () {
            let currentBackgroundImage = $(".lazy-p").eq(this.realIndex - 1).css("backgroundImage");
            $(".slide-time-ios").css("backgroundImage", currentBackgroundImage);
          }
        }
      });

      let youtubeSwiper = new Swiper(".you-ku", {
        "loop": true,
        "slidesPerView": 1,
        "autoplay": {
          "disableOnInteraction": false
        },
        "pagination": {
          "el": ".swiper-pagination"
        },
        "on": {
          "slideNextTransitionEnd": function () {
            $(".slide-nav-list li").eq(this.realIndex).addClass("on").siblings().removeClass("on");
            updateSlideNavContent();
          }
        }
      });

      $(".slide-nav-list li").hover(function () {
        youtubeSwiper.slideTo($(".slide-nav-list li").index(this) + 1, 1000, false);
        $(this).addClass("on").siblings().removeClass("on");
        updateSlideNavContent();
      });

      function updateSlideNavContent() {
        let activeSlideContent = $(".slid-g .swiper-slide-active .this-100").html();
        $(".slide-nav-link").html("<div class=\"this-100\">" + activeSlideContent + "</div>");
      }
    },

    "Roll": function () {
      new Swiper(".roll", {
        "paginationClickable": true,
        "slidesPerView": "auto"
      });
    }
  }
  ,
  "Actor": {
    "Detail": function () {
      new Swiper(".list-swiper", {
        "slidesPerView": 3,
        "slidesPerGroup": 3,
        "observer": true,
        "navigation": {
          "nextEl": ".swiper-button-next",
          "prevEl": ".swiper-button-prev"
        },
        "breakpoints": {
          2200: {
            "slidesPerView": 10,
            "slidesPerGroup": 10
          },
          1934: {
            "slidesPerView": 9,
            "slidesPerGroup": 9
          },
          1692: {
            "slidesPerView": 8,
            "slidesPerGroup": 8
          },
          1330: {
            "slidesPerView": 7,
            "slidesPerGroup": 7
          },
          767: {
            "freeMode": true,
            "slidesPerView": "auto",
            "slidesPerGroup": 1
          }
        }
      });

      let actorApiContainer = $(".star-works .actor-api");

      if (actorApiContainer.length > 0) {
        if ($(".star-works .public-list-box").length > 0) {
          loadActorWorks($(".star-active").attr("data-actor"));
        } else {
          $(".star-works").hide();
        }
      }

      $(".star-works-top .public-list-box").click(function () {
        $(this).addClass("star-active").siblings().removeClass("star-active");
        loadActorWorks($(this).attr("data-actor"));
      });

      function loadActorWorks(actorName) {
        let skeletonHtml = "";

        for (let itemIndex = 0; itemIndex < 9; itemIndex++) {
          skeletonHtml = skeletonHtml +
            "<div class=\"public-list-box public-pic-b swiper-slide\">" +
            "<div class=\"public-list-div\">" +
            "<a class=\"public-list-exp\">" +
            "<div class=\"lazy box\">" +
            "<span class=\"loadIcon spin-dot-spin\">" +
            "<i class=\"spin-dot-item\"></i>" +
            "<i class=\"spin-dot-item\"></i>" +
            "<i class=\"spin-dot-item\"></i>" +
            "<i class=\"spin-dot-item\"></i>" +
            "</span>" +
            "</div>" +
            "</a>" +
            "</div>" +
            "<div class=\"public-list-button\">" +
            "<a class=\"time-title box radius\"></a>" +
            "</div>" +
            "</div>";
        }

        actorApiContainer.html(skeletonHtml);

        EC.Ajax(maccms.path + "/index.php/api/actor_vod_api?name=" + actorName, "get", "json", "", function (response) {
          if (Number(response.code) === 1) {
            skeletonHtml = "";

            $.each(response.list, function (itemIndex, vodItem) {
              skeletonHtml = skeletonHtml +
                " <div class=\"public-list-box public-pic-" + response.ajax.vod_pic + " swiper-slide\">\n" +
                " <div class=\"public-list-div public-list-bj\">\n" +
                " <a" + HTML.Target(response.ajax.vod_target) + " class=\"public-list-exp\" href=\"" + vodItem.url + "\">\n" +
                " <img class=\"lazy lazy1 gen-movie-img " + maccms.vod_mask + "\" alt=\"" + vodItem.name + "\" referrerpolicy=\"no-referrer\" data-src=\"" + vodItem.pic + "\" />" +
                " <span class=\"public-bg\"></span>\n" +
                " <span class=\"public-play\"><i class=\"fa\">&#xe593;</i></span>\n" +
                " </a>\n" +
                " </div>\n" +
                " <div class=\"public-list-button\">" +
                "<a" + HTML.Target(response.ajax.vod_target) + " class=\"time-title ft4 hide\" href=\"" + vodItem.url + "\" title=\"" + vodItem.name + "\">" + vodItem.name + "</a>" +
                "</div>" +
                " </div>";
            });

            actorApiContainer.html(skeletonHtml);
            EC.Init.LazyLoad.update();

            if ($(".lang-bnt").length === 1) {
              zh_tranBody();
            }
          } else {
            _EC.Pop.Msg(language["39"], "", "error");
          }
        });
      }
    }
  }
  ,
  "Pop": {
    "Uid": "sEn6hYRlH6qTkaLv",
    "Drawer": function (drawerContent, callback) {
      if ($(".drawer-list").length > 0) {
        _EC.Pop.Msg(language["40"], "", "error");
        return;
      }

      $("body").append("<div class=\"drawer-list\"><div class=\"drawer-list-bg box-bg ease\" style=\"display:none\"></div><div class=\"drawer-list-box bj3\"></div></div>");
      $(".drawer-list-box").html(drawerContent);
      callback();

      setTimeout(function () {
        $(".drawer-list-bg").css({ "display": "block" });
        $("body").css({ "height": "100%", "width": "100%", "overflow": "hidden" });
        $(".drawer-list").addClass("drawer-show");
      }, 0);

      $(".drawer-list-bg").on("click", function () {
        EC.Pop.DrawerDel();
      }).on("touchmove", function () {
        EC.Pop.DrawerDel();
      });
    },

    "DrawerDel": function () {
      $(".drawer-list-box").addClass("drawer-out");
      $("body").css({ "height": "", "width": "", "overflow": "" });
      $(".drawer-list-bg").css({ "display": "none" });

      setTimeout(function () {
        $(".drawer-list").remove();
      }, 100);
    },

    "Show": function (windowContent, windowTitle, callback) {
      if (Number($(".window").length) !== 1) {
        EC.Pop.RemoveWin();
      }

      $("body").append("<div class=\"window\"><div class=\"box-bg\"></div><div class=\"window-box\"><div class=\"topfadeInUp animated bj3 cor4\"><div class=\"window-title rel\"><h2 class=\"subscript ft4 br\"></h2><a class=\"window-off fa ds-guanbi\"></a></div><div class=\"window-content\"></div></div></div></div>");

      $(".window .subscript").html(windowTitle);
      $(".window-content").html(windowContent);
      $(".window-box").addClass("window-show");

      $(document).on("click", ".box-bg", function () {
        $(this).parent().remove();
      });

      $(document).on("click", ".window-off", function () {
        $(this).parent().parent().parent().parent().remove();
      });

      callback();
    },

    "RemoveWin": function () {
      $(".window").remove();
    }
  },

  "Toggle": function () {
    $(".switch-button a").click(function () {
      $(this).addClass("selected").siblings().removeClass("selected");

      let selectedIndex = $(".switch-button a").index(this);
      let targetContent = $(".switch-box .check").eq(selectedIndex);

      targetContent.fadeIn(800).siblings().hide();
      targetContent.addClass("selected").siblings().removeClass("selected");
    });
  },

  "User": {
    "BoxShow": 0,
    "IsLogin": 0,

    "Init": function () {
      if (!EC.Empty(EC.Cookie.Get("user_id"))) {
        EC.User.IsLogin = 1;
      }

      $(document).on("click", ".head-user", function () {
        if (EC.Empty(EC.Cookie.Get("user_id"))) {
          EC.User.Login();
        } else {
          window.location.href = $(this).attr("data-url");
        }
      });

      $(document).on("click", ".head-user-out", function () {
        EC.User.Logout();
      });
    },

    "Login": function () {
      EC.Ajax(maccms.path + "/index.php/user/ajax_login", "post", "json", "", function (loginFormData) {
        EC.Pop.Show(loginFormData, language["41"], function () {
          $("body").on("click", "#wp-submit", function () {
            $(this).unbind("click");
            EC.Ajax(maccms.path + "/index.php/user/login", "post", "json", $(".login-form").serialize(), function (loginResponse) {
              _EC.Pop.Msg(loginResponse.msg, "", "error");
              if (Number(loginResponse.code) === 1) {
                location.reload();
              }
            });
          });
        });
      }, function () {
        _EC.Pop.Msg(language["42"], "", "error");
      });
    },

    "Logout": function () {
      $(this).unbind("click");
      EC.Ajax(maccms.path + "/index.php/user/logout", "post", "json", "", function (logoutResponse) {
        _EC.Pop.Msg(logoutResponse.msg);
        if (Number(logoutResponse.code) === 1) {
          location.reload();
        }
      });
    },

    "Collection": function () {
      $("body").on("click", ".collection", function () {
        if (Number(EC.User.IsLogin) === 0) {
          EC.User.Login();
          return;
        }

        let collectionButton = $(this);

        if (collectionButton.attr("data-id")) {
          EC.Ajax(maccms.path + "/index.php/user/ajax_ulog/?ac=set&mid=" + collectionButton.attr("data-mid") + "&id=" + collectionButton.attr("data-id") + "&type=" + collectionButton.attr("data-type"), "get", "json", "", function () {
            _EC.Pop.Msg("收藏成功");
          });
        }
      });
    }
  },

  "Ulog": {
    "Init": function () {
      EC.Ulog.Set();
    },

    "Set": function () {
      let ulogElement = $(".ds-log-set");

      if (ulogElement.attr("data-mid")) {
        $.get(maccms.path + "/index.php/api/ulog/?ac=set&mid=" + ulogElement.attr("data-mid") + "&id=" + ulogElement.attr("data-id") + "&sid=" + ulogElement.attr("data-sid") + "&nid=" + ulogElement.attr("data-nid") + "&type=" + ulogElement.attr("data-type"));
      }
    }
  },

  "Hits": {
    "Init": function () {
      let hitsElement = $(".ds-hits");

      if (Number(hitsElement.length) === 0) return;

      EC.Ajax(maccms.path + "/index.php/ajax/hits?mid=" + hitsElement.attr("data-mid") + "&id=" + hitsElement.attr("data-id") + "&type=update", "get", "json", "", function (hitsResponse) {
        if (Number(hitsResponse.code) === 1) {
          $(".ds-hits").each(function (elementIndex) {
            let dataType = $(".ds-hits").eq(elementIndex).attr("data-type");
            if (dataType !== "insert") {
              $(".ds-hits").eq(elementIndex).html(hitsResponse.data[dataType]);
            }
          });
        }
      });
    }
  },

  "Md5": function (inputString) {
    return hex_md5("yuyuyy" + inputString + EC.Pop.Uid);
  },

  "Score": function () {
    let hasScored = 0;

    $(document).on("click", "#rating li", function () {
      if (hasScored > 0) {
        _EC.Pop.Msg(language["43"]);
      } else {
        let ratingData = $("#rating").data();

        EC.Ajax(maccms.path + "/index.php/ajax/score?mid=" + ratingData.mid + "&id=" + ratingData.id + "&score=" + $(this).attr("val") * 2, "post", "json", "", function (scoreResponse) {
          _EC.Pop.Msg(scoreResponse.msg);
          hasScored = 1;
        }, function () {
          _EC.Pop.Msg(language["44"], "", "error");
        });
      }

      $(this).nextAll().removeClass("active");
      $(this).prevAll().addClass("active");
      $(this).addClass("active");
    });
  }
  ,
  "Suggest": {
    "Init": function (inputSelector, moduleId) {
      if (Number(maccms.so_off) === 1) {
        try {
          $(inputSelector).autocomplete(maccms.path + "/index.php/ajax/suggest?mid=" + moduleId, {
            "inputClass": "search-input",
            "resultsClass": "results",
            "loadingClass": "loading",
            "appendTo": ".completion",
            "minChars": 1,
            "matchSubset": 0,
            "cacheLength": 10,
            "multiple": false,
            "matchContains": true,
            "autoFill": false,
            "dataType": "json",
            "parse": function (responseData) {
              if (Number(responseData.code) === 1) {
                let parsedResults = [];
                $.each(responseData.list, function (itemIndex, itemData) {
                  itemData.url = responseData.url;
                  parsedResults[itemIndex] = { "data": itemData };
                });
                return parsedResults;
              } else {
                return { "data": "" };
              }
            },
            "formatItem": function (itemData) {
              return itemData.name;
            },
            "formatResult": function (resultData) {
              return resultData.text;
            }
          }).result(function (event, selectedItem) {
            $(inputSelector).val(selectedItem.name);
            let targetUrl = selectedItem.url.replace("mac_wd", encodeURIComponent(selectedItem.name));
            EC.Records.Set(selectedItem.name, targetUrl);
            location.href = targetUrl;
          });
        } catch (error) { }
      }
    }
  },

  "History": {
    "BoxShow": 0,
    "Limit": 30,
    "Days": 7,
    "Json": "",
    "Msg": "<div class=\"null cor5\"><img src=\"" + maccms.path2 + "template/yuyuyy/asset/img/null.png\" alt=\"" + language["2"] + "\"><span>" + language["45"] + "</span></div>",

    "Init": function () {
      let historyData = [];
      if (this.Json) {
        historyData = this.Json;
      } else {
        let cookieHistory = EC.Cookie.Get("mac_history");
        if (!EC.Empty(cookieHistory)) {
          historyData = eval(cookieHistory);
        }
      }

      let historyHtml = "";
      if (historyData.length > 0) {
        for (let itemIndex = 0; itemIndex < historyData.length; itemIndex++) {
          historyHtml += "<li><a class=\"history-a flex\" href=\"" + historyData[itemIndex].link + "\" target=\"video\" title=\"" + historyData[itemIndex].name + "\"><img class=\"lazy lazy1\" referrerpolicy=\"no-referrer\" alt=\"" + historyData[itemIndex].name + "\" data-src=\"" + historyData[itemIndex].pic + "\"/>" + "<div class=\"history-r\"><div class=\"hide2\">" + historyData[itemIndex].name + "</div><div><span class=\"cor5\">" + historyData[itemIndex].mid + "</span></div></div></a></li>";
        }
      } else {
        historyHtml += this.Msg;
      }

      $(".locality-history ul").html(historyHtml);

      $("#l_history").click(function () {
        EC.History.Clear();
      });

      let historySetElement = $(".ds-history-set");
      if (historySetElement.attr("data-name")) {
        EC.History.Set(historySetElement.attr("data-name"), historySetElement.attr("data-link"), historySetElement.attr("data-pic"), historySetElement.attr("data-mid"));
      }
    },

    "Set": function (itemName, itemLink, itemPic, itemMid) {
      if (!itemLink) {
        itemLink = document.URL;
      }

      let existingHistory = EC.Cookie.Get("mac_history");
      let historyString = "";

      if (!EC.Empty(existingHistory)) {
        this.Json = eval(existingHistory);

        for (let itemIndex = 0; itemIndex < this.Json.length; itemIndex++) {
          if (this.Json[itemIndex].link === itemLink) {
            return false;
          }
        }

        historyString = "{log:[{\"name\":\"" + itemName + "\",\"link\":\"" + itemLink + "\",\"pic\":\"" + itemPic + "\",\"mid\":\"" + itemMid + "\"},";

        for (let itemIndex = 0; itemIndex < this.Json.length; itemIndex++) {
          if (itemIndex <= this.Limit && this.Json[itemIndex]) {
            let existingName = this.Json[itemIndex].name;
            if (existingName === itemName) {

            } else {
              historyString += "{\"name\":\"" + this.Json[itemIndex].name + "\",\"link\":\"" + this.Json[itemIndex].link + "\",\"pic\":\"" + this.Json[itemIndex].pic + "\",\"mid\":\"" + this.Json[itemIndex].mid + "\"},";
            }
          } else {
            break;
          }
        }

        historyString = historyString.substring(0, historyString.lastIndexOf(","));
        historyString += "]}";
      } else {
        historyString = "{log:[{\"name\":\"" + itemName + "\",\"link\":\"" + itemLink + "\",\"pic\":\"" + itemPic + "\",\"mid\":\"" + itemMid + "\"}]}";
      }

      this.Json = eval(historyString);
      EC.Cookie.Set("mac_history", historyString, this.Days);
    },

    "Clear": function () {
      EC.Cookie.Del("mac_history");
      $(".locality-history ul").html(this.Msg);
    }
  },

  "Records": {
    "Limit": 8,
    "Days": 7,
    "Json": "",

    "Init": function () {
      EC.Records.Click();

      let recordsData = [];
      if (this.Json) {
        recordsData = this.Json;
      } else {
        let cookieRecords = EC.Cookie.Get("DS_Records");
        if (!EC.Empty(cookieRecords)) {
          recordsData = eval(cookieRecords);
        }
      }

      if (recordsData.length > 0) {
        let recordsHtml = "";
        for (let itemIndex = 0; itemIndex < recordsData.length; itemIndex++) {
          recordsHtml += "<a href=\"" + recordsData[itemIndex].url + "?wd=" + recordsData[itemIndex].name + "\" class=\"public-list-b bj border\">" + recordsData[itemIndex].name + "</a>";
        }
        $(".records-list").html(recordsHtml);
      } else {
        $(".records-list").html("<span class=\"cor5\">" + language["46"] + "</span>");
      }

      $(document).on("click", "#re_del", function () {
        EC.Records.Clear();
      });
    },

    "Set": function (recordName, recordUrl) {
      let existingRecords = EC.Cookie.Get("DS_Records");
      let recordsString = "";

      if (!EC.Empty(existingRecords)) {
        this.Json = eval(existingRecords);

        for (let itemIndex = 0; itemIndex < this.Json.length; itemIndex++) {
          if (this.Json[itemIndex].name === recordName) {
            return false;
          }
        }

        recordsString = "{log:[{\"name\":\"" + recordName + "\",\"url\":\"" + recordUrl + "\"},";

        for (let itemIndex = 0; itemIndex < this.Json.length; itemIndex++) {
          if (itemIndex <= this.Limit && this.Json[itemIndex]) {
            let existingName = this.Json[itemIndex].name;
            if (existingName === recordName) {

            } else {
              recordsString += "{\"name\":\"" + this.Json[itemIndex].name + "\",\"url\":\"" + this.Json[itemIndex].url + "\"},";
            }
          } else {
            break;
          }
        }

        recordsString = recordsString.substring(0, recordsString.lastIndexOf(","));
        recordsString += "]}";
      } else {
        recordsString = "{log:[{\"name\":\"" + recordName + "\",\"url\":\"" + recordUrl + "\"}]}";
      }

      this.Json = eval(recordsString);
      EC.Cookie.Set("DS_Records", recordsString, this.Days);
    },

    "Clear": function () {
      EC.Cookie.Del("DS_Records");
      $(".records-list").html("<span class=\"cor5\">" + language["47"] + "</span>");
    },

    "Click": function () {
      $(".head .this-select").click(function () {
        if ($(this).attr("data-id") === "1") {
          $(this).attr("data-id", "0").find("i").html("&#xe564;");
          let selectContent = $(".select-list .ease").html();
          $(".head .this-search").append("<div class=\"this-search-select bj radius br cor4\">" + selectContent + "</div>");
        } else {
          $(this).attr("data-id", "1").find("i").html("&#xe563;");
          $(".head .this-search-select").remove();
        }
      });

      $("body").on("click", ".head .this-search-select span", function () {
        let selectData = $(this).data();
        $(".head .this-select").html(selectData.name + "<i class=\"fa\">&#xe563;</i>").attr("data-id", "1");
        $("#search2").attr("action", selectData.url);
        $(".head .this-search-select").remove();
      });

      $(".head .this-search .ds-sousuo").click(function () {
        let searchValue = $(".head .this-input").val();
        let searchAction = $("#search2").attr("action");

        if (EC.Empty(searchValue)) {
          event.preventDefault();
          _EC.Pop.Msg(language["48"], "", "error");
        } else {
          if ($(".lang-bnt").length === 1) {
            const translatedValue = transChinese(searchValue);
            $(".head .this-input").val(translatedValue);
            searchValue = translatedValue;
          }
          EC.Records.Set(searchValue, searchAction);
        }
      });

      $(".head .this-input").click(function () {
        let newContent = $(".public-list-new").html();
        let hotContent = $(".pop-list-body .wap-diy-vod-e").html();
        $(".head .this-search").append("<div class=\"this-search-get\"><div class=\"box radius\"><div>" + newContent + "</div>" + "<div class=\"wap-diy-vod-e search-hot\">" + hotContent + "</div>" + "</div></div>");
      }).keydown(function () {
        $(".head .this-search-get").remove();
      });

      $(".head .this-search").mouseleave(function () {
        $(".head .this-search-get").remove();
      });

      $(".select-name").click(function () {
        if ($(this).attr("data-id") === "1") {
          $(this).attr("data-id", "0").children().html("&#xe564;");
          $(".select-list").show();
        } else {
          $(this).attr("data-id", "1").children().html("&#xe563;");
          $(".select-list").hide();
        }
      });

      $(".select-list span").click(function () {
        let selectData = $(this).data();
        $(".select-name").html(selectData.name + "<i class=\"fa cor4\">&#xe563;</i>").attr("data-id", "1");
        $("#search").attr("action", selectData.url);
        $(".select-list").hide();
      });

      $(".search-input-sub").click(function () {
        let searchValue = $(".search-input").val();
        let searchAction = $("#search").attr("action");

        if (EC.Empty(searchValue)) {
          event.preventDefault();
          _EC.Pop.Msg(language["48"], "", "error");
        } else {
          if ($(".lang-bnt").length === 1) {
            const translatedValue = transChinese(searchValue);
            $(".search-input").val(translatedValue);
            searchValue = translatedValue;
          }
          EC.Records.Set(searchValue, searchAction);
        }
      });
    }
  },

  "Remaining": function (inputElement, maxLength, displayElement) {
    let remainingChars = maxLength - $(inputElement).val().length;
    if (remainingChars < 0) {
      remainingChars = 0;
      $(inputElement).val($(inputElement).val().substr(0, 200));
    }
    $(displayElement).text(remainingChars);
  },

  "Digg": function () {
    $("body").on("click", ".digg-link", function () {
      let diggButton = $(this);
      if (diggButton.attr("data-id")) {
        EC.Ajax(maccms.path + "/index.php/ajax/digg.html?mid=" + diggButton.attr("data-mid") + "&id=" + diggButton.attr("data-id") + "&type=" + diggButton.attr("data-type"), "get", "json", "", function (diggResponse) {
          diggButton.addClass("disabled");
          if (Number(diggResponse.code) === 1) {
            if (diggButton.attr("data-type") === "up") {
              diggButton.find(".digg-num").html(diggResponse.data.up);
            } else {
              diggButton.find(".digg-num").html(diggResponse.data.down);
            }
          } else {
            _EC.Pop.Msg(diggResponse.msg);
          }
        });
      }
    });
  }
  ,
  "Gbook": {
    "Login": 0,
    "Verify": 0,

    "Init": function () {
      let gbookFormData = $(".gbook-form").data();
      EC.Gbook.Login = gbookFormData.login;
      EC.Gbook.Verify = gbookFormData.verify;

      let bodyElement = $("body");

      bodyElement.on("keyup", ".gbook-content", function () {
        EC.Remaining($(this), 200, ".gbook_remaining");
      });

      bodyElement.on("focus", ".gbook-content", function () {
        if (Number(EC.Gbook.Login) === 1 && Number(EC.User.IsLogin) !== 1) {
          EC.User.Login();
        }
      });

      bodyElement.on("click", ".gbook-submit", function () {
        EC.Gbook.Submit();
      });
    },

    "Show": function (pageNumber) {
      EC.Ajax(maccms.path + "/index.php/gbook/index?page=" + pageNumber, "post", "json", "", function (responseData) {
        $(".mac_gbook_box").html(responseData);
      }, function () {
        $(".mac_gbook_box").html(language["49"]);
      });
    },

    "Submit": function () {
      if (EC.Empty($(".gbook-content").val())) {
        _EC.Pop.Msg(language["50"], "", "error");
        return false;
      }

      EC.Ajax(maccms.path + "/index.php/gbook/saveData", "post", "json", $(".gbook-form").serialize(), function (submitResponse) {
        _EC.Pop.Msg(submitResponse.msg);

        if (Number(submitResponse.code) === 1) {
          location.reload();
        } else {
          if (Number(EC.Gbook.Verify) === 1) {
            EC.Verify.Refresh();
          }
        }
      });
    },

    "Tg": function () {
      if (EC.Empty($(".tg-content").val())) {
        _EC.Pop.Msg(language["51"], "", "error");
        return false;
      }

      EC.Ajax(maccms.path + "/index.php/api/tougao", "post", "json", $(".tg-form").serialize(), function (tgResponse) {
        _EC.Pop.Msg(tgResponse.msg);

        if (Number(tgResponse.code) === 1) {
          location.reload();
        } else {
          EC.Verify.Refresh();
        }
      });
    },

    "Report": function (reportData) {
      EC.Ajax(maccms.path + "/index.php/gbook/report.html?id=" + reportData.id + "&name=" + encodeURIComponent(reportData.url + location.href), "post", "json", "", function (reportResponse) {
        EC.Pop.Show(reportResponse, language["52"], function () {
          let gbookFormData = $(".gbook-form").data();
          EC.Gbook.Login = gbookFormData.login;
          EC.Gbook.Verify = gbookFormData.verify;
          EC.Gbook.Init();
        });
      }, function () {
        _EC.Pop.Msg(language["49"], "", "error");
      });
    }
  }
  ,
  "Comment": {
    "Login": 0,
    "Verify": 0,

    "Init": function () {
      let bodyElement = $("body");

      bodyElement.on("click", ".comment-face-box img", function () {
        let commentContentElement = $(this).parent().parent().parent().parent().find(".comment-content");
        $(commentContentElement).val($(commentContentElement).val() + "[em:" + $(this).attr("data-id") + "]");
      });

      bodyElement.on("click", ".comment-face-panel", function () {
        $(this).parent().parent().find(".comment-face-box").fadeToggle();
      });

      bodyElement.on("click", ".face-close", function () {
        $(".comment-face-box").hide();
      });

      bodyElement.on("click", ".comment-page", function () {
        EC.Comment.Show($(this).attr("data-page"));
      });

      bodyElement.on("keyup", ".comment-content", function () {
        EC.Remaining($(this), 200, $(".comment-remaining"));
      });

      bodyElement.on("focus", ".comment-content", function () {
        if (Number(EC.Comment.Login) === 1 && Number(EC.User.IsLogin) !== 1) {
          EC.User.Login();
        }
      });

      bodyElement.on("click", ".comment-report", function () {
        let reportButton = $(this);
        if ($(this).attr("data-id")) {
          EC.Ajax(maccms.path + "/index.php/comment/report.html?id=" + reportButton.attr("data-id"), "get", "json", "", function (reportResponse) {
            reportButton.addClass("disabled");
            _EC.Pop.Msg(language["53"], "", "success");
          });
        }
      });

      bodyElement.on("click", ".comment-reply-button", function () {
        let replyButton = $(this);
        if (replyButton.attr("data-id")) {
          let buttonText = replyButton.html();
          $(".comment-reply-form").remove();

          if (buttonText === language["54"]) {
            replyButton.html("&#xe573;");
            return false;
          }

          let originalForm = $(".comment-form").prop("outerHTML");
          let replyForm = $(originalForm);
          replyForm.addClass("comment-reply-form");
          replyForm.find("input[name=\"comment_pid\"]").val(replyButton.attr("data-id"));
          replyButton.parent().parent().after(replyForm);

          $(".comment-reply-button").html("&#xe573;");
          replyButton.html(language["54"]);
        }
      });

      bodyElement.on("click", ".comment-submit", function () {
        let submitButton = $(this);
        EC.Comment.Submit(submitButton);
      });
    },

    "Show": function (pageNumber) {
      let commentContainer = $(".ds-comment");
      if (commentContainer.length > 0) {
        EC.Ajax(maccms.path + "/index.php/comment/ajax.html?rid=" + commentContainer.attr("data-id") + "&mid=" + commentContainer.attr("data-mid") + "&page=" + pageNumber, "get", "json", "", function (commentData) {
          $(".ds-comment").html(commentData);
          if ($(".lang-bnt").length === 1) {
            zh_tranBody();
          }
        }, function () {
          _EC.Pop.Msg(language["49"], "", "error");
        });
      }
    },

    "Submit": function (submitButton) {
      let formElement = submitButton.parents("form");

      if ($(formElement).find(".comment-content").val() === "") {
        _EC.Pop.Msg(language["55"], "", "error");
        return false;
      }

      let commentData = $(".ds-comment").data();

      if (EC.Empty(commentData.mid)) {
        _EC.Pop.Msg(language["56"], "", "error");
        return false;
      }

      if (EC.Empty(commentData.id)) {
        _EC.Pop.Msg(language["57"], "", "error");
        return false;
      }

      EC.Ajax(maccms.path + "/index.php/comment/saveData", "post", "json", $(formElement).serialize() + "&comment_mid=" + commentData.mid + "&comment_rid=" + commentData.id, function (submitResponse) {
        _EC.Pop.Msg(submitResponse.msg);

        if (Number(submitResponse.code) === 1) {
          EC.Comment.Show(1);
        } else {
          if (Number(EC.Comment.Verify) === 1) {
            EC.Verify.Refresh();
          }
        }
      });
    }
  }
  ,
  "Verify": {
    "Init": function () {
      EC.Verify.Click();

      $(".verify-submit").click(function () {
        let verifyCode = $("input[name=\"verify\"]").val();

        EC.Ajax(maccms.path + "/index.php/ajax/verify_check?type=" + $(this).data("type") + "&verify=" + verifyCode, "post", "json", "", function (verifyResponse) {
          if (Number(verifyResponse.code) === 1) {
            location.reload();
          } else {
            _EC.Pop.Msg(verifyResponse.msg);
            EC.Verify.Refresh();
          }
        }, function () {
          _EC.Pop.Msg(language["58"], "", "error");
          EC.Verify.Refresh();
        });
      });
    },

    "Click": function () {
      $("body").on("click", "img.ds-verify-img", function () {
        $(this).attr("src", maccms.path + "/index.php/verify/index.html?r=" + Math.random());
      });
    },

    "Refresh": function () {
      $(".ds-verify-img").attr("src", maccms.path + "/index.php/verify/index.html?r=" + Math.random());
    },

    "Show": function () {
      return "<img class=\"ds-verify-img\" src=\"" + maccms.path + "/index.php/verify/index.html?\" alt=\"" + language["19"] + "\" title=\"" + language["59"] + "\">";
    }
  }
  ,
  "QiAnDao": {
    "Fun": function (signedDays) {
      let calendarList = $("#qiAnDao-list");
      let calendarHtml = "";
      let currentDate = new Date();
      let firstDayOfMonth = new Date(currentDate.getFullYear(), parseInt(currentDate.getMonth()), 1).getDay();
      let lastDateOfMonth = new Date(currentDate.getFullYear(), parseInt(currentDate.getMonth() + 1), 0);
      let totalDaysInMonth = lastDateOfMonth.getDate();

      $(".qiAnDao-bj").text(currentDate.getMonth() + 1);

      for (let cellIndex = 0; cellIndex < 42; cellIndex++) {
        calendarHtml += "<li></li>";
      }
      calendarList.html(calendarHtml);

      let calendarCells = calendarList.find("li");

      for (let dayIndex = 0; dayIndex < totalDaysInMonth; dayIndex++) {
        let dayNumber = parseInt(dayIndex + 1);
        calendarCells.eq(dayIndex + firstDayOfMonth).html("<em>" + dayNumber + "</em>").addClass("date" + dayNumber);

        if (signedDays.length > 0) {
          for (let signedIndex = 0; signedIndex < signedDays.length; signedIndex++) {
            if (dayNumber === signedDays[signedIndex]) {
              calendarCells.eq(dayIndex + firstDayOfMonth).addClass("nav-dt");
            }
          }
        }
      }

      $(".qiAnDao-con").after("<style>#qiAnDao-list li:nth-child(n+" + (totalDaysInMonth + firstDayOfMonth + 1) + ") {display: none}</style>").addClass("qiAnDao-show");

      $(".date" + currentDate.getDate() + ":not(.nav-dt)").addClass("able-qiAnDao");

      $(".qiAnDao-top,.able-qiAnDao").click(function () {
        EC.Ajax(maccms.path + "/index.php/qian/sign", "get", "json", "", function (signResponse) {
          if (Number(signResponse.code) === 0) {
            _EC.Pop.Msg(signResponse.msg, "", "error");
          } else {
            _EC.Pop.Msg(language["60"] + signResponse.reward, "", "success");
            $(".able-qiAnDao").addClass("nav-dt");
            $(".qiAnDao-top").addClass("nav-dt");
          }
        }, function () {
          _EC.Pop.Msg(r.msg, "", "error");
        });
      });

      $(".qiAnDao-gz-bnt").click(function () {
        $(".qiAnDao-bottom").show();
      });

      $(".qiAnDao-bottom a").click(function () {
        $(".qiAnDao-bottom").hide();
      });

      $(".qiAnDao-gz-off").click(function () {
        $(".qiAnDao-con").hide();
      });
    },

    "Init": function () {
      $("#qiAnDao_bnt").click(function () {
        if (Number(EC.User.IsLogin) === 0) {
          EC.User.Login();
          return;
        }

        if (Number($(".qiAnDao-show").length) === 0) {
          $("#qiAnDao_2,.qiAnDao-top").hide();
          $("#qiAnDao_1,.qiAnDao-con").show();

          EC.Ajax(maccms.path + "/index.php/qian/days", "get", "json", "", function (daysResponse) {
            if (Number(daysResponse.code) === 1) {
              EC.QiAnDao.Fun(daysResponse.data);
              setTimeout(function () {
                $("#qiAnDao_2,.qiAnDao-top").show();
                $("#qiAnDao_1").hide();
              }, 1000);
            } else {
              $(".qiAnDao-con").hide();
              _EC.Pop.Msg(daysResponse.msg, "", "error");
            }
          }, function () {
            $("#qiAnDao_1").html(language["42"]);
          });
        } else {
          $(".qiAnDao-show").show();
        }
      });
    }
  }

};
$(function () {
  EC.Style.Init();
  EC.Init();
  EC.Verify.Init();
  EC.User.Init();
  EC.Records.Init();
  EC.Suggest.Init(".mac_wd", 1, "");
  EC.History.Init();
  EC.Digg();
  EC.Score();
  EC.Copy.Init();
  EC.User.Collection();
  EC.Ulog.Init();
  EC.Hits.Init();
  EC.Toggle();
  EC.QiAnDao.Init();
});