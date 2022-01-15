
class Printer_noozle{
  //Attributs
  constructor(x,y,newNoozleSize,newPrinterSize){
    this.x = x;
    this.y = y;
    this.noozleSize = newNoozleSize;
    this.printerSize = newPrinterSize;
    this.filamentColor = "blue";
    this.printer_journey = new Array(400).fill(new Array(2));
  }
  set_new_position(newX,newY){
    this.x = newX;
    this.y = newY;
    if(active_extruder){
      x_filament_pos_on_bed.push(newX);
      y_filament_pos_on_bed.push(newY);
    }
  }
  init_printer_journey(){
    for(var line=0;line<this.printer_journey.length;line++){
      for(var column=0;column<this.printer_journey.length;column++){
        this.printer_journey[line][column] = 0;
      }
    }
  }
  get_x_position(){
    return this.x;
  }
  get_y_position(){
    return this.y;
  }
  change_filament_color(string){
    this.filamentColor = string;
  }
  display_printer_head(ctx){
    //Tête d'impression
    ctx.fillStyle = "black";
    ctx.fillRect(this.x,this.y,this.printerSize,this.printerSize);

  }
  extrude(){
    for(var i=0;i<x_filament_pos_on_bed.length;i++){
      //Tracé (de la taille de la buse)
      ctx.fillStyle = this.filamentColor;
      ctx.fillRect(x_filament_pos_on_bed[i]+this.printerSize/3,y_filament_pos_on_bed[i]+this.printerSize/3,this.noozleSize,this.noozleSize);
    }
  }
  save_printer_pos(){
    if((px>=200 && px<=400)&&(py>=200 && py<=400)&&(active_extruder==true)){
      for(var k=0;k<10;k++){
        this.printer_journey[px-200+k][py-200+k] = 1;
      }

    }
  }
}


//INIT
const canvas = document.getElementById('canvas');
const ctx = canvas.getContext('2d');
document.addEventListener("keydown",keyPush);

//CREATION DE L'IMPRIMANTE
printer = new Printer_noozle(30,30,10,40);
let px=py=30; //Stockent la future pos de la buse
let dx=dy=0; //Stockent la pression des touches au clavier
let active_extruder = false;
let x_filament_pos_on_bed = [];//Stocke pos x extrudeur
let y_filament_pos_on_bed = [];//Stocke pos y extrudeur
let x_figure_pos_on_bed = [];
let y_figure_pos_on_bed = [];
let user_score = 0;
printer.init_printer_journey();
compute_square_pos();
console.log("INIT OK");


function launch_game(){
  setTimeout(function onTick() {
    display_tray();
    display_square();
    move_printer();
    launch_game();
  }, 100)
}

function move_printer(){
  px+=dx;py+=dy;
  //On ne sort pas du plateau de jeu
  if(px<printer.printerSize/2){
    px+=10;
  }
  if(px>canvas.width-printer.printerSize){
    px-=10;
  }
  if(py<printer.printerSize/2){
    py +=10;
  }
  if(py>canvas.height-printer.printerSize){
    py -=10;
  }
  printer.set_new_position(px,py);
  printer.save_printer_pos();
  printer.extrude();
  printer.display_printer_head(ctx);
}

//NIVEAU CARRE
function display_tray(){
  //PLATEAU
  ctx.fillStyle = "grey";
  ctx.fillRect(20,20,canvas.width,canvas.height);
}

function compute_square_pos(){
  for(var i=0;i<200;i++){
    for(var a=0;a<200;a++){
      x_figure_pos_on_bed.push(200+i);
      y_figure_pos_on_bed.push(200+a);
    }
  }
}

function display_square(){
  for(var i=0;i<x_figure_pos_on_bed.length;i++){
    ctx.fillStyle = "lime";
    ctx.fillRect(x_figure_pos_on_bed[i],y_figure_pos_on_bed[i],10,10);
  }
}

function end_check(){
  //METHODE TROP GOURMANDE CA FAIT TOUT CRASH...
  /*//On regarde s'il reste des pixels verts dans le canvas
  var end_print = false;
  // Fetch the imageData object
  var imageData = ctx.getImageData(200,200,200,200);
  // Pull the pixel color data array from the imageData object
  var pixelDataArray = imageData.data;
  if(pixelDataArray!=pixels_color){
    end_print = true;
  }
  for(var k=1;k<pixelDataArray.length;k+2){
    if(pixelDataArray[k]!=pixels_color[k]){
      pixels_count += 1;
    }
  }
  if(pixels_count == 4000){
    end_print= true;
  }*/
  var end_print = true;
  for(var x=0;x<200;x++){
    for(var y=0;y<200;y++){
      if(printer.printer_journey[x][y]!=1){
        end_print = false;
      }
    }
  }
  console.log(px);console.log(py);
  console.log();
  console.log(printer.printer_journey);
  if(end_print==true){
    alert("C'est gagné");
  }
}

/********************EVENT***************/
function keyPush(evt) {
  evt.preventDefault(); //On désactive le scroll de la page via les touches du clavier
  switch(evt.keyCode) {
      case 37:
          dx=-10;dy=0;
          //alert("fleche gauche");
          break;
      case 38:
          dx=0;dy=-10;
          //alert("fleche du bas");
          break;
      case 39:
          dx=+10;dy=0;
          //alert("fleche de droite");
          break;
      case 40:
          dx=0;dy=+10;
          //alert("fleche du haut");
          break;
      case 32:
          if(!active_extruder){
            active_extruder = true;
          }
          else{
            active_extruder = false;
          }
          //alert("space bar pressed");
          break;
      case 13:
        end_check();
        alert("END CHECK");
        break;
  }
}
