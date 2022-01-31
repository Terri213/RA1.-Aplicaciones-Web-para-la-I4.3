<?php include("../template/cabecera.php") ?>
<?php 
$txtID=(isset($_POST['txtID']))?$_POST['txtID']:"";
$txtNombre=(isset($_POST['txtNombre']))?$_POST['txtNombre']:"";
$txtImagen=(isset($_FILES['txtImagen']['name']))?$_FILES['txtImagen']['name']:"";
$accion=(isset($_POST['accion']))?$_POST['accion']:"";


include("../config/bd.php");

switch($accion){

    case "Agregar";
    $sentenciaSQL= $conexion->prepare("INSERT INTO productos (Nombre, Imagen) VALUES (:nombre,:imagen);");
    $sentenciaSQL->bindparam(':nombre',$txtNombre);

    $fecha= new DateTime();
    $nombreArchivo=($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES["txtImagen"]["name"]:"imagen.jpg";

    $tmpImagen=$_FILES["txtImagen"]["tmp_name"];

    if($tmpImagen!=""){

        move_uploaded_file($tmpImagen,"../../img/".$nombreArchivo);
    }

    $sentenciaSQL->bindparam(':imagen',$nombreArchivo);
    $sentenciaSQL->execute();

    header("location:productos.php");
    break;
    
    case "Modificar";
    $sentenciaSQL= $conexion->prepare("UPDATE productos SET nombre=:nombre WHERE id=:id");
    $sentenciaSQL->bindparam(':nombre',$txtNombre);
    $sentenciaSQL->bindparam(':id',$txtID);
    $sentenciaSQL->execute();


   if($txtImagen!=""){
    $fecha= new DateTime();
    $nombreArchivo=($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES["txtImagen"]["name"]:"imagen.jpg";
    $tmpImagen=$_FILES["txtImagen"]["tmp_name"];

    move_uploaded_file($tmpImagen,"../../img/".$nombreArchivo);
    $sentenciaSQL= $conexion->prepare("SELECT imagen From productos WHERE id=:id");
    $sentenciaSQL->bindparam(':id',$txtID);
    $sentenciaSQL->execute();
    $producto=$sentenciaSQL->fetch(PDO::FETCH_LAZY);

    if(isset($producto["imagen"]) &&($producto["imagen"]!="imagen.pgj")) {
      
        if(file_exists("../../img/".$producto["imagen"])){

            unlink("../../img/".$producto["imagen"]);
        }
    }
    $sentenciaSQL= $conexion->prepare("UPDATE productos SET imagen=:imagen WHERE ID=:ID");
    $sentenciaSQL->bindparam(':Imagen',$txtImagen);
    $sentenciaSQL->bindparam(':ID',$txtID);
    $sentenciaSQL->execute();
    }
    header("location:productos.php");
    break;


    case "Cancelar";

    header("location:productos.php");

    break;

    case "Seleccionar";
    $sentenciaSQL= $conexion->prepare("SELECT * From productos WHERE ID=:ID");
    $sentenciaSQL->bindparam(':ID',$txtID);
    $sentenciaSQL->execute();
    $productos=$sentenciaSQL->fetch(PDO::FETCH_LAZY);

    $txtNombre=$productos['Nombre'];
    $txtImagen=$productos['Imagen'];
    break;

    case "Borrar";
 $sentenciaSQL= $conexion->prepare("SELECT imagen From productos WHERE ID=:ID");
    $sentenciaSQL->bindparam(':ID',$txtID);
    $sentenciaSQL->execute();
    $productos=$sentenciaSQL->fetch(PDO::FETCH_LAZY);

    if(isset($producto["Imagen"]) &&($productos["Imagen"]!="imagen.pgj")) {
      
        if(file_exists("../../img/".$productos["Imagen"])){

            unlink("../../img/".$productos["Imagen"]);
            
            header("location:productos.php");
        }
    }
     $sentenciaSQL= $conexion->prepare("DELETE FROM productos WHERE ID=:ID");
    $sentenciaSQL->bindparam(':ID',$txtID);
    $sentenciaSQL->execute();

break;
}

$sentenciaSQL= $conexion->prepare("SELECT * FROM productos");
$sentenciaSQL->execute();
$listaproductos=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="col-md-5">
    <div class="card">
        <div class="card-header">
          Datos del Producto
        </div>

        <div class="card-body">
        <form method="POST" enctype="multipart/form-data">

<div class="form-group">
<label for="txtID">ID</label>
<input type="text" required readonly class="form-control" value="<?php echo $txtID; ?>" name="txtID" id="txtID" placeholder="ID">
</div>

<div class="form-group">
<label for="txtNombre">Nombre</label>
<input type="txtNombre" class="form-control" value="<?php echo $txtNombre; ?>" name="txtNombre" id="txtNombre" placeholder="Nombre">
</div>

<div class="form-group">
<label for="txtImagen">Imagen</label>

<br/>

<?php  if($txtImagen!=""){  ?>

    <img class="img-thumbnail rounded" src="/login/img/ $txtImagen; ?>"  width="50" alt="" srcset="">

<?php } ?>

<input type="file" class="form-control" name="txtImagen" id="txtImagen" placeholder="Imagen" placeholder="Imagen">
</div>

<div class="btn-group" role="group" aria-label="">
    <button type="submit" name="accion" <?php echo ($accion=="Seleccionar")?"disable":"" ?> value="Agregar" class="btn btn-success">Agregar</button>
    <button type="submit" name="accion" <?php echo ($accion!="Seleccionar")?"disable":"" ?> value="Modificar" class="btn btn-warning">Modificar</button>
    <button type="submit" name="accion" <?php echo ($accion!="Seleccionar")?"disable":"" ?> value="Cancelar" class="btn btn-info">Cancelar</button>
    </div>
    </form>
        </div>

    </div>


</div>
<div class="col-md-7">
  <table class="table table-bordered">
      <thead>
          <tr>
              <th>ID</th>
              <th>NOMBRE</th>
              <th>IMAGEN</th>
              <th>ACCIONES</th>
          </tr>
      </thead>
      <tbody>

        <?php foreach($listaproductos as $listaproductos)   {?>
            <tr>
              <td><?php echo $listaproductos['ID']; ?></td>
              <td><?php echo $listaproductos['Nombre'];?></td>
            
          <td>

              <img class="img-thumbnail rounded"  src="../../img/<?php echo $listaproductos['Imagen'];  ?>"  width="90" alt="" srcset="">
            
            </td>

            <td>
        <form method="post">

        <input type="hidden" name="txtID" id="txtID" value="<?php echo $listaproductos['ID'];?>"/>
 
        <input type="submit" name="accion" value=" Seleccionar " class="btn btn-primary"/>       

        <input type="submit" name="accion" value="Borrar" class="btn btn-danger"/>

            
</form>
        
            </td>
            </td>
          </tr>
          <?php } ?>

          
      </tbody>
  </table>

</div>

<?php include("../template/pie.php")?>

