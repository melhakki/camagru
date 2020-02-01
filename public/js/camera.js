(function() {
    // The width and height of the captured photo. We will set the
    // width to the value defined here, but the height will be
    // calculated based on the aspect ratio of the input stream.
  
    var width = 320;    // We will scale the photo width to this
    var height = 0;     // This will be computed based on the input stream
  
    // |streaming| indicates whether or not we're currently streaming
    // video from the camera. Obviously, we start at false.
  
    var streaming = false;
  
    // The various HTML elements we need to configure or control. These
    // will be set by the startup() function.
  
    var video = null;
    var canvas = null;
    var photo = null;
    var startbutton = null;
    var stk = 'none';
  
    function startup() {
      video = document.getElementById('video');
      canvas = document.getElementById('canvas');
      photo = document.getElementById('photo');
      startbutton = document.getElementById('startbutton');
  
      var camera_is_enabled = 0;
      if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        //Not adding `{ audio: true }` since we only want video now
        navigator.mediaDevices.getUserMedia({ video: true }).then(function (stream) {
            //video.src = window.URL.createObjectURL(stream);
            if ("srcObject" in video) {
                video.srcObject = stream;
            }
            else {
                video.src = window.URL.createObjectURL(stream);
            }
            video.play();
            camera_is_enabled = 1;
        })/*
        .catch(function () {
            camera_is_enabled = 0;
            studioMessage.innerHTML = "Camera not detected !";
            studioMessageBox.style.display = "block";
        }); */
      }
  
      video.addEventListener('canplay', function(ev){
        if (!streaming) {
          height = video.videoHeight / (video.videoWidth/width);
        
          // Firefox currently has a bug where the height can't be read from
          // the video, so we will make assumptions if this happens.
        
          if (isNaN(height)) {
            height = width / (4/3);
          }
        
          video.setAttribute('width', width);
          video.setAttribute('height', height);
          canvas.setAttribute('width', width);
          canvas.setAttribute('height', height);
          streaming = true;
        }
      }, false);
  
      startbutton.addEventListener('click', function(ev){
        var radios = document.getElementsByName('stickers');
        for (var i = 0, length = radios.length; i < length; i++)
        {
            if (radios[i].checked)
            {
                stk = radios[i].value;
                break;
            }
        }
        if (stk != 'none')
        {
          document.getElementById('startbutton').disabled = false;
          takepicture();
          ev.preventDefault();
        }else {
          alert("Choose a sticker");
          document.getElementById('startbutton').disabled = true;
          location.reload();
        }
      }, false);
      
      // clearphoto();
    }
  
    // Fill the photo with an indication that none has been
    // captured.
  
    // function clearphoto() {
    //   var context = canvas.getContext('2d');
    //   context.fillStyle = "#AAA";
    //   context.fillRect(0, 0, canvas.width, canvas.height);
  
    //   var data = canvas.toDataURL('image/png');
    //    photo.setAttribute('src', data);
    //   var xhr = new XMLHttpRequest();
    //   xhr.open('POST', 'http://localhost/camagru/camera/save_pic');
    //   xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //   xhr.onreadystatechange = function() {
    //       if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
    //           console.log(this.responseText);
    //       }
    //   }
    //   alert(stk);
    //   xhr.send('img=' + encodeURIComponent(data) + '&stk=' + stk);
    // }
    
    // Capture a photo by fetching the current contents of the video
    // and drawing it into a canvas, then converting that to a PNG
    // format data URL. By drawing it on an offscreen canvas and then
    // drawing that to the screen, we can change its size and/or apply
    // other changes before drawing it.
  
    function takepicture() {
      var context = canvas.getContext('2d');
      if (width && height) {
        canvas.width = width;
        canvas.height = height;
        context.drawImage(video, 0, 0, width, height);
      
        var data = canvas.toDataURL('image/png');
         photo.setAttribute('src', data);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'http://localhost/camagru/camera/take_pic');
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                console.log(this.responseText);
            }
        }
        
        xhr.send('img=' + encodeURIComponent(data) + '&stk=' + stk + '&from=video');
      } else {
        // clearphoto();
      }
    }
  
    // Set up our event listener to run the startup process
    // once loading is complete.
    window.addEventListener('load', startup, false);
  })();

  var width = 320;
  var height = 0;

  height = width / (4/3);

  function uploadimg(){
    if(window.File && window.FileReader && window.FileList && window.Blob)
    {
      var file  = document.querySelector('input[type=file]').files[0];
      if(file.size > 0)
      {
        var reader  = new FileReader();
        reader.readAsDataURL(file);
        var stk = 'none';
        reader.onloadend = function (e) {
          var img = new Image();
          img.src = e.target.result;
          img.onload = function(ev){
            var  canvas = document.getElementById('canvas');
            var ctx = canvas.getContext('2d');
            canvas.width = width;
            canvas.height = height;
            ctx.drawImage(img, 0, 0, width, height);
          }
          var radios = document.getElementsByName('stickers');
          for (var i = 0, length = radios.length; i < length; i++) {
              if (radios[i].checked) {
                  stk = radios[i].value;
                  break;
              }
          }
          var data = this.result;
          var xhr = new XMLHttpRequest();
          xhr.open('POST', 'http://localhost/camagru/camera/take_pic');
          xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
          xhr.onreadystatechange = function () {
              if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                  // console.log(this.responseText);
              }
          }
          xhr.send('img=' + encodeURIComponent(data) + '&stk=' + stk + '&from=upload');
        }
      }
    }
  };