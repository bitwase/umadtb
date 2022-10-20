<!DOCTYPE html>
<html >
  <head>
    <meta charset="UTF-8">
    <meta name="google" value="notranslate">


    <title>CodePen - A Pen by  Sara Vieira</title>
    
    
    <link rel="stylesheet" href="//codepen.io/assets/reset/reset.css">

    
        <style>
      .drag {
  width: 200px;
  height: 200px;
  background: blue;
  display: inline-block;
  margin-right: 10px;
}
#boxB{
  margin-right: 0px;
}
#big {
  width: 415px;
  height: 400px;
  background: red;
  margin: 20px auto;
}
section {
width: 415px;
  height: 200px;
  background:gray;
  margin: 20px auto;
}
    </style>    
  </head>

  <body>

    <h1>Drag the blue boxes into the red box and back</h1>
<div id="big" ondragenter="return dragEnter(event)"
     ondrop="return dragDrop(event)" 
     ondragover="return dragOver(event)"></div>
<section id="section"  ondragenter="return dragEnter(event)" 
     ondrop="return dragDrop(event)" 
     ondragover="return dragOver(event)">
<div class="drag" id="boxA" draggable="true" ondragstart="return dragStart(event)"></div>
<div class="drag" id="boxB" draggable="true" ondragstart="return dragStart(event)"></div>
</section>
      <script src="//assets.codepen.io/assets/common/stopExecutionOnTimeout-f961f59a28ef4fd551736b43f94620b5.js"></script>

    <script src='//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

        <script>
      function dragStart(ev) {
    ev.dataTransfer.effectAllowed = 'move';
    ev.dataTransfer.setData('Text', ev.target.getAttribute('id'));
    ev.dataTransfer.setData('Dest', getAttribute(this.id));
    ev.dataTransfer.setDragImage(ev.target, 100, 100);
    return true;
}
function dragEnter(ev) {
    event.preventDefault();
    return true;
}
function dragOver(ev) {
    event.preventDefault();
}
function dragDrop(ev) {
    var data = ev.dataTransfer.getData('Text');
var dest = ev.dataTransfer.getData('Dest');
//var dest = target.getAttribute('id');
alert('Arrastou '+dest+ 'para');
    ev.target.appendChild(document.getElementById(data));
    ev.stopPropagation();
    return false;
}
      //@ sourceURL=pen.js
    </script>

    
    <script>
  if (document.location.search.match(/type=embed/gi)) {
    window.parent.postMessage("resize", "*");
  }
</script>

    
  </body>
</html>
 

