  const player = new Plyr('#videoPlayer', {
    controls: ['play-large', 'play', 'progress', 'current-time', 'mute', 'volume', 'fullscreen'],
    keyboard: { focused: true, global: true }
  });

  var video = document.querySelector('#videoPlayer');
  var videoSrc = video.querySelector('source');
  
  if (Hls.isSupported()) {
    var hls = new Hls();
    hls.loadSource(videoSrc.src);
    hls.attachMedia(video);
  } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
    videoSrc.src = videoSrc.src;
  } else {
    console.error('HLS is not supported in this browser.');
  }