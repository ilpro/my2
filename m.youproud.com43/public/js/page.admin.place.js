// $('#save-place').click(function (){
//   var data = {
//     name: $('#place-name').val().trim(),
//     photo: self.photos
//   }
// });
var PagePlace = {
  socket: dataSocket,
  savePlaceInProgress: false,

  placeId: false,
  imageAttachments: [],
  videoAttachments: [],
  location: {
    address: false,
    city: false,
    lat: 40.7127753, // New York coordinates by default
    lng: -74.0059728, // New York coordinates by default
  },

  _map: false,

  init: function () {
    var self = this;

    if ($('#ad_places').is('[data-place-id]')) self.placeId = +$('#ad_places').attr('data-place-id');
    if ($('#ad_places').is('[data-images]')) self.imageAttachments = JSON.parse($('#ad_places').attr('data-images'));
    if ($('#ad_places').is('[data-videos]')) self.videoAttachments = JSON.parse($('#ad_places').attr('data-videos'));

    if ($('#ad_places').is('[data-address]')) self.location.address = $('#ad_places').attr('data-address');
    if ($('#ad_places').is('[data-city]')) self.location.city = $('#ad_places').attr('data-city');
    if ($('#ad_places').is('[data-lat]')) self.location.lat = +$('#ad_places').attr('data-lat');
    if ($('#ad_places').is('[data-lng]')) self.location.lng = +$('#ad_places').attr('data-lng');

    self.initSocketHandlers();
    self.initEventHandlers();
    self.initAttachments();
    self.initMap();
  },

  /**
   * Event handlers like "click"
   */
  initEventHandlers: function () {
    var self = this;

    // Add/Update place
    $('#save-place').click(function () {
      if (!self.checkConditionsBeforeSend()) return;

      if (self.savePlaceInProgress) return;
      self.savePlaceInProgress = true;

      var reqData = {
        hash: userHash,
        name: $('#place-name').val().trim(),
        details: $('#place-details').val().trim(),
        phone: $('#place-phone').val().trim(),
        email: $('#place-email').val().trim(),
        site: $('#place-site').val().trim(),
        placeId: self.placeId,
        images: self.imageAttachments,
        location: self.location,
      };

      self.socket.emit('saveCityPlace', JSON.stringify(reqData));
    });

    // remove attachment
    $(document).on('click', ".picture-box .close-cross", function (e) {
      e.preventDefault();

      var $imageBox = $(this).closest('.picture-box');
      var type = $imageBox.find('video').length ? 'video' : 'image';

      if (type === 'image') {
        var src = $imageBox.find('img').attr('src');
        var imageIndex = self.imageAttachments.findIndex(function (elem) {
          return elem.path === src
        });
        // remove image from self.imageAttachments
        self.imageAttachments.splice(imageIndex, 1);
      }

      if (type === 'video') {
        var src = $imageBox.find('video source').attr('src');
        var videoIndex = self.videoAttachments.findIndex(function (elem) {
          return elem.path === src
        });
        // remove image from self.imageAttachments
        self.videoAttachments.splice(videoIndex, 1);
      }
      $imageBox.remove();
    });
  },

  /**
   * Socket handlers listeners
   */
  initSocketHandlers: function () {
    var self = this;

    // add post
    self.socket.off('saveCityPlace').on('saveCityPlace', function (data) {
      self.savePlaceInProgress = false;

      const parsedData = JSON.parse(data);
      if (!parsedData.success) return alert(parsedData.message);

      if (window.location.pathname.indexOf('/places/add') != -1) {
        self.cleanInputs();
      }

      alert(`Place was successfully saved under ID ${parsedData.placeId}!`);
    });
  },

  /**
   * Post attachment section
   */
  initAttachments: function () {
    var self = this;

    $('#add-attachment').ajaxUpload({
      url: "/places/upload-image",
      name: "image",
      onSubmit: function () {
        var loaderHtml = self.imageContainer();
        $('#attachments').append(loaderHtml).show();
        return true;
      },
      onComplete: function (res) {
        var resObj = JSON.parse(res).img;
        if (!resObj.success) return alert(resObj.message);

        var imageObj = {path: resObj.path};

        // if obj has screenshot - it is a video file
        if (resObj.thumbPath) {
          imageObj.thumbPath = resObj.thumbPath;
          // prepare attachment for send to server
          self.videoAttachments.push(imageObj);


          var attachmentHtml = self.videoContainer(imageObj);
          $('.loader.empty').remove();
          $('#attachments').append(attachmentHtml).show();
        } else {
          imageObj.width = resObj.width;
          imageObj.height = resObj.height;

          // image file
          // prepare attachment for send to server
          self.imageAttachments.push(imageObj);

          var attrObj = {
            src: imageObj.path,
            'data-image-width': imageObj.width,
            'data-image-height': imageObj.height,
          };
          $('.loader.empty').removeClass('empty').find('img').attr(attrObj);
        }
      }
    });
  },

  initMap: function () {
    var self = this;

    var coordinates = {lat: self.location.lat, lng: self.location.lng};

    self._map = new google.maps.Map(document.getElementById('gmap_canvas'), {
      center: coordinates,
      zoom: 16,
      mapTypeId: 'roadmap'
    });

    self._setMarkerOnMap(coordinates, $('#place-name').val());

    /// input handle
    // Create the search box and link it to the UI element.
    var searchBox = new google.maps.places.SearchBox(document.getElementById('place-address'));

    // Bias the SearchBox results towards current map's viewport.
    self._map.addListener('bounds_changed', function () {
      searchBox.setBounds(self._map.getBounds());
    });

    // Listen for the event fired when the user selects a prediction and retrieve
    // more details for that place.
    searchBox.addListener('places_changed', function () {
      var placeInfo = searchBox.getPlaces()[0];
      // if (!placeInfo.geometry) {
      //   // User entered the name of a Place that was not suggested and
      //   // pressed the Enter key, or the Place Details request failed.
      //   $('#place-address').attr('placeholder', 'Sorry, nothing found');
      //   return;
      // }

      var request = {placeId: placeInfo.place_id};
      var service = new google.maps.places.PlacesService(self._map);
      service.getDetails(request, function (place, status) {
        if (status !== google.maps.places.PlacesServiceStatus.OK) {
          return $('#place-address').attr('placeholder', 'Sorry, nothing found');
        }

        // get country name from response
        var countryObj = placeInfo.address_components.filter(function (elem) {
          return elem.types.includes('country');
        });
        var country = countryObj[0].long_name;

        // get city name from response
        var cityObj = placeInfo.address_components.filter(function (elem) {
          return elem.types.includes('locality') || elem.types.includes('administrative_area_level_1')
        });
        var city = cityObj[0].long_name;


        console.log(city,',',country);
        // setup concatenated city
        self.location.city = city + ',' + country;

        // get latitude and longitude of place
        self.location.lat = placeInfo.geometry.location.lat();
        self.location.lng = placeInfo.geometry.location.lng();

        // get english full name
        self.location.address = placeInfo.formatted_address;

        // For each place, get the icon, name and location.
        var bounds = new google.maps.LatLngBounds();

        self._setMarkerOnMap(placeInfo.geometry.location, placeInfo.name);

        if (placeInfo.geometry.viewport) {
          // Only geocodes have viewport.
          bounds.union(placeInfo.geometry.viewport);
        } else {
          bounds.extend(placeInfo.geometry.location);
        }

        self._map.fitBounds(bounds);
      });
    });
  },

  _setMarkerOnMap(coordinates, name) {
    var self = this;

    new google.maps.Marker({
      map: self._map,
      position: coordinates,
      title: name
    });
  },

  imageContainer: function (source, identifier) {
    var id = identifier ? 'data-image-id="' + identifier + '"' : '';

    var src = '/img/loader.gif';
    var empty = 'empty';
    if (source) {
      src = source;
      empty = '';
    }

    var imageBox =
      '<div class="picture-box loader ' + empty + '">\
       <div class="close-cross">' + svgObj.closeCross() + '</div>\
       <img src="' + src + '" ' + id + '>\
       </div>';

    return imageBox;
  },

  /** Helper functions **/
  checkConditionsBeforeSend: function () {
    var self = this;
    $('.validate').removeAttr('style');

    if (!$('#place-name').val().trim().length) {
      $('#place-name').css('border', '1px solid red').focus();
      return false;
    }

    if (!$('#place-address').val().trim().length) {
      $('#place-address').css('border', '1px solid red').focus();
      return false;
    }

    return true
  },

  cleanInputs: function () {
    var self = this;

    // clear inputs
    $('#place-name, #place-details, #place-city, #place-address, #place-phone, #place-email, #place-site').val('');

    // clear photos
    self.placeId = false;
    self.imageAttachments = [];
    self.videoAttachments = [];
    $('#attachments').empty();

    // clear location
    self.location = {};

    $("html, body").animate({scrollTop: 0}, "fast");
  },
};

PagePlace.init();