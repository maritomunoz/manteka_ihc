<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'controllers/Master.php'; 

class Secciones extends MasterManteka {


	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */

	public function index() //Esto hace que el index sea la vista que se desee
	{

		$this->verSecciones();
	}

	/**
	* Ver una seccion del sistema y luego carga los datos para volver a la vista 'cuerpo_secciones_ver'
	* Primero se comprueba que el usuario tenga la sesión iniciada, en caso que no sea así se le redirecciona al login
	* Siguiente a esto se cargan los datos para las plantillas de la página.
	* Se carga el modelo de secciones,finalmente se carga la vista nuevamente con todos los datos para permitir ver otra seccion.
	*
	*/
	public function verSecciones()
	{
		
		// se carga el modelo, los datos de la vista, las funciones a utilizar del modelo
		$datos_vista = 0;		
		$subMenuLateralAbierto = "verSecciones"; 
		$muestraBarraProgreso = FALSE; //Indica si se muestra la barra que dice anterior - siguiente
		$tipos_usuarios_permitidos = array();
		$tipos_usuarios_permitidos[0] = TIPO_USR_COORDINADOR; $tipos_usuarios_permitidos[1] = TIPO_USR_PROFESOR;
		$this->load->model('Model_secciones');
		$cod_seccion = $this->input->post("cod_seccion");
        $datos_vista = array('seccion' =>$this->Model_secciones->VerTodasSecciones(),'rs_estudiantes'=>$this->Model_secciones->VerTodosLosEstudiantes($cod_seccion),'secc' =>$this->Model_secciones->VerSeccion($cod_seccion));
		$this->cargarTodo("Secciones", 'cuerpo_secciones_ver', "barra_lateral_secciones", $datos_vista, $tipos_usuarios_permitidos, $subMenuLateralAbierto, $muestraBarraProgreso);
	}

	/**
	* Función llamada por una vista a través de una petición AJAX
	* Esta función rescata, a través de la vista, la variable 'sección'
	* Dicha variable se guarda en otra de tipo local para llamar a otra función, en el modelo
	* Finalmente, el resultado de la función en el modelo se le convierte en su representacion JSON
	* y se le envía como un string a la vista
	*/

	public function postVerSeccion() {
		//Se comprueba que quien hace esta petición de ajax esté logueado
		if (!$this->isLogged()) {
			//echo 'No estás logueado!!';
			return;
		}

		$cod_seccion = $this->input->post('seccion');
		$this->load->model('Model_secciones');
		$resultado = $this->Model_secciones->VerSeccion($cod_seccion);
		echo json_encode($resultado);
	}

	/**
	* Función llamada por una vista a través de una petición AJAX
	* Esta función rescata, a traves de la vista, las variables que corresponden a
	* la letra de la seccion y a los dos digitos que acompañan la letra de la sección
	* Estas variables se utilizan como parametro para llamar a otra función, en el modelo
	* Finalmente, el resultado de la función en el modelo se le conveierte en su representación JSON
	* y se le envía como un string a la vista
	*/

	public function secExiste() {
		//Se comprueba que quien hace esta petición de ajax esté logueado
		if (!$this->isLogged()) {
			//echo 'No estás logueado!!';
			return;
		}

		$letra_post = $this->input->post('letra_post');
		$num_post = $this->input->post('num_post');
		$cod_post = $this->input->post('cod_post');
		$this->load->model('Model_secciones');
		$resultado = $this->Model_secciones->existeSeccion($cod_post,$letra_post,$num_post);
		echo json_encode($resultado);
	}

	/**
	* Función llamada por una vista a través de una petición AJAX
	* Esta función rescata, a través de la vista, la variable 'sección'
	* Dicha variable se guarda en otra de tipo local para llamar a otra función, en el modelo
	* Finalmente, el resultado de la función en el modelo se le convierte en su representacion JSON
	* y se le envía como un string a la vista
	*/

	public function AlumnosSeccion() {
		//Se comprueba que quien hace esta petición de ajax esté logueado
		if (!$this->isLogged()) {
			//echo 'No estás logueado!!';
			return;
		}

		$cod_seccion = $this->input->post('seccion');
		$this->load->model('Model_secciones');
		$resultado = $this->Model_secciones->VerTodosLosEstudiantes($cod_seccion);
		echo json_encode($resultado);
	}

	/**
	* Agregar una seccion del sistema y luego carga los datos para volver a la vista 'cuerpo_secciones_agregar'
	* Primero se comprueba que el usuario tenga la sesión iniciada, en caso que no sea así se le redirecciona al login
	* Siguiente a esto se cargan los datos para las plantillas de la página.
	* Se carga el modelo de secciones, se llama a la función AgregarSeccion para insertar la seccion
	* con los datos que se capturan un paso antes en el controlador desde la vista con el uso del POST.
	* El resultado de ésta se recibe en la variable 'confirmacion'
	* que se le envía a la vista a través de la variable 'mensaje_confirmacion' para que de el feedback al usuario, en la vista, de como resulto la operación.
	* Finalmente se carga la vista nuevamente con todos los datos para permitir la inserción de otra seccion.
	*/
	public function agregarSecciones()
	{	
		
		// se carga el modelo, los datos de la vista, las funciones a utilizar del modelo
		$datos_vista = 0;		
		$subMenuLateralAbierto = "agregarSecciones"; 
		$muestraBarraProgreso = FALSE; //Indica si se muestra la barra que dice anterior - siguiente
		$tipos_usuarios_permitidos = array();
		$tipos_usuarios_permitidos[0] = TIPO_USR_COORDINADOR;
		$this->load->model('Model_secciones');
		$this->cargarTodo("Secciones", 'cuerpo_secciones_agregar', "barra_lateral_secciones", $datos_vista, $tipos_usuarios_permitidos, $subMenuLateralAbierto, $muestraBarraProgreso);

	}

	/**
	* Función que se ejecuta al presionar el botón "Agregar" en la vista "Agregar secciones"
	* Esta funcion recibe las entradas escritas correspondientes a la letra y a los dos dígitos,
	* escritos como el nombre de la sección, y los utiliza como parametros de la función del modelo
	* Se analiza el resultado de la función del modelo y dependiendo del resultado se muestra la
	* vista de confirmación "Acción Realizada" o "Acción No Realizada"
	*/

	public function ingresarSecciones()
	{	
		
		
		$this->load->model('Model_secciones');
		$nombre_seccion1 = $this->input->post("rs_seccion");
		$nombre_seccion2 = $this->input->post("rs_seccion2");
		$confirmacion = $this->Model_secciones->AgregarSeccion($nombre_seccion1,$nombre_seccion2);
        
		if ($confirmacion==1){
			$datos_plantilla["titulo_msj"] = "Acción Realizada";
			$datos_plantilla["cuerpo_msj"] = "Se ha ingresado la sección con éxito";
			$datos_plantilla["tipo_msj"] = "alert-success";
		}
		else{
			$datos_plantilla["titulo_msj"] = "Acción No Realizada";
			$datos_plantilla["cuerpo_msj"] = "Ha ocurrido un error en el ingreso en base de datos";
			$datos_plantilla["tipo_msj"] = "alert-error";	
		}
		$datos_plantilla["redirectAuto"] = FALSE; //Esto indica si por javascript se va a redireccionar luego de 5 segundos
		$datos_plantilla["redirecTo"] = "Secciones/agregarSecciones"; //Acá se pone el controlador/metodo hacia donde se redireccionará
		$datos_plantilla["nombre_redirecTo"] = "Agregar Secciones"; //Acá se pone el nombre del sitio hacia donde se va a redireccionar
		$tipos_usuarios_permitidos = array();
		$tipos_usuarios_permitidos[0] = TIPO_USR_COORDINADOR;
		$this->cargarMsjLogueado($datos_plantilla, $tipos_usuarios_permitidos);

	}

	
 
 	/**
	* Editar una seccion del sistema y luego carga los datos para volver a la vista 'cuerpo_secciones_editar'
	* Primero se comprueba que el usuario tenga la sesión iniciada, en caso que no sea así se le redirecciona al login
	* Siguiente a esto se cargan los datos para las plantillas de la página.
	* Se carga el modelo de secciones, se llama a la función AtualizarSeccion para editar la seccion
	* con los datos que se capturan un paso antes en el controlador desde la vista con el uso del POST.
	* El resultado de ésta se recibe en la variable 'confirmacion'
	* que se le envía a la vista a través de la variable 'mensaje_confirmacion' para que de el feedback al usuario, en la vista, de como resulto la operación.
	* Finalmente se carga la vista nuevamente con todos los datos para permitir la edición de otra seccion.
	*
	*/
    public function editarSecciones()
    {
	//Se comprueba que quien hace esta petición este logueado
    	$rut = $this->session->userdata('rut'); //Se comprueba si el usuario tiene sesi?n iniciada
		if ($rut == FALSE) {
			redirect('/Login/', ''); //Se redirecciona a login si no tiene sesi?n iniciada
		}
		// se carga el modelo, los datos de la vista, las funciones a utilizar del modelo
		$datos_vista = 0;		
		$subMenuLateralAbierto = "editarSecciones"; 
		$muestraBarraProgreso = FALSE; //Indica si se muestra la barra que dice anterior - siguiente
		$tipos_usuarios_permitidos = array();
		$tipos_usuarios_permitidos[0] = TIPO_USR_COORDINADOR;
		$this->load->model('Model_secciones');
        $datos_vista = array('seccion' =>$this->Model_secciones->VerTodasSecciones());
		$this->cargarTodo("Secciones", 'cuerpo_secciones_editar', "barra_lateral_secciones", $datos_vista, $tipos_usuarios_permitidos, $subMenuLateralAbierto, $muestraBarraProgreso);
    
    }

    /**
    * Función que se ejecuta al presionar el botón "Guardar" de la vista "Editar secciones"
    * Se reciben las entradas correspondientes al codigo de la sección y al nombre de la sección,
    * separadas en la letra y en los dos digitos de ella, para utilizarlos en la función del modelo.
    * Se analiza el resultado de la función del modelo y dependiendo del resultado se muestra la
	* vista de confirmación "Acción Realizada" o "Acción No Realizada"
    */

    public function modificarSecciones()
    {
	
		
		$this->load->model('Model_secciones');
		$cod_seccion = $this->input->post("cod_seccion");
		$nombre_seccion1 = $this->input->post("rs_seccion");
		$nombre_seccion2 = $this->input->post("rs_seccion2");
		$confirmacion = $this->Model_secciones->ActualizarSeccion($cod_seccion,$nombre_seccion1,$nombre_seccion2);
        
        // se muestra mensaje de operación realizada
    	if ($confirmacion==1){
			$datos_plantilla["titulo_msj"] = "Acción Realizada";
			$datos_plantilla["cuerpo_msj"] = "Se ha modificado la sección con éxito";
			$datos_plantilla["tipo_msj"] = "alert-success";
		}
		else{
			$datos_plantilla["titulo_msj"] = "Acción No Realizada";
			$datos_plantilla["cuerpo_msj"] = "Ha ocurrido un error en la edición en base de datos";
			$datos_plantilla["tipo_msj"] = "alert-error";	
		}
		$datos_plantilla["redirectAuto"] = FALSE; //Esto indica si por javascript se va a redireccionar luego de 5 segundos
		$datos_plantilla["redirecTo"] = "Secciones/editarSecciones"; //Acá se pone el controlador/metodo hacia donde se redireccionará
		$datos_plantilla["nombre_redirecTo"] = "Editar Secciones"; //Acá se pone el nombre del sitio hacia donde se va a redireccionar
		$tipos_usuarios_permitidos = array();
		$tipos_usuarios_permitidos[0] = TIPO_USR_COORDINADOR;
		$this->cargarMsjLogueado($datos_plantilla, $tipos_usuarios_permitidos);
    }



		/**
	* Borrar una seccion del sistema y luego carga los datos para volver a la vista 'cuerpo_secciones_borrar'
	* Primero se comprueba que el usuario tenga la sesión iniciada, en caso que no sea así se le redirecciona al login
	* Siguiente a esto se cargan los datos para las plantillas de la página.
	* Se carga el modelo de secciones, se llama a la función EliminarSeccion para borrar la seccion
	* con los datos que se capturan un paso antes en el controlador desde la vista con el uso del POST.
	* El resultado de ésta se recibe en la variable 'confirmacion'
	* que se le envía a la vista a través de la variable 'mensaje_confirmacion' para que de el feedback al usuario, en la vista, de como resulto la operación.
	* Finalmente se carga la vista nuevamente con todos los datos para permitir la eliminación de otra seccion.
	*
	*/
    public function borrarSecciones()
    {
		
		// se carga el modelo, los datos de la vista, las funciones a utilizar del modelo
		$datos_vista = 0;		
		$subMenuLateralAbierto = "borrarSecciones"; 
		$muestraBarraProgreso = FALSE; //Indica si se muestra la barra que dice anterior - siguiente
		$tipos_usuarios_permitidos = array();
		$tipos_usuarios_permitidos[0] = TIPO_USR_COORDINADOR;
		$this->load->model('Model_secciones');
		//$cod_seccion = $this->input->post("cod_seccion");
		//$cod_seccion1 = $this->input->post("rs_seccion");
		//$confirmacion = $this->Model_secciones->EliminarSeccion($cod_seccion1);
        $datos_vista = array('seccion' =>$this->Model_secciones->VerTodasSecciones()/*,'rs_estudiantes'=>$this->Model_secciones->VerTodosLosEstudiantes($cod_seccion),'secc' =>$this->Model_secciones->VerSeccion($cod_seccion),'mensaje_confirmacion'=>$confirmacion*/);
		$this->cargarTodo("Secciones", 'cuerpo_secciones_borrar', "barra_lateral_secciones", $datos_vista, $tipos_usuarios_permitidos, $subMenuLateralAbierto, $muestraBarraProgreso);
	}

	/**
	* Función que se ejecuta al presionar el botón "Eliminar" en la vista "Borrar secciones"
	* Esta funcion recibe la entrada correspondientes al codigo de la seccion 
	* y los utiliza como parametros de la función del modelo
	* Se analiza el resultado de la función del modelo y dependiendo del resultado se muestra la
	* vista de confirmación "Acción Realizada" o "Acción No Realizada"
	*/

	public function eliminarSecciones()
    {
		
		$this->load->model('Model_secciones');
		$cod_seccion = $this->input->post("cod_seccion");
		$confirmacion = $this->Model_secciones->EliminarSeccion($cod_seccion);
        
		if ($confirmacion==1){
			$datos_plantilla["titulo_msj"] = "Acción Realizada";
			$datos_plantilla["cuerpo_msj"] = "Se ha eliminado la sección con éxito";
			$datos_plantilla["tipo_msj"] = "alert-success";
		}
		else{
			$datos_plantilla["titulo_msj"] = "Acción No Realizada";
			$datos_plantilla["cuerpo_msj"] = "Ha ocurrido un error en la eliminación en base de datos";
			$datos_plantilla["tipo_msj"] = "alert-error";	
		}
		$datos_plantilla["redirectAuto"] = FALSE; //Esto indica si por javascript se va a redireccionar luego de 5 segundos
		$datos_plantilla["redirecTo"] = "Secciones/borrarSecciones"; //Acá se pone el controlador/metodo hacia donde se redireccionará
		$datos_plantilla["nombre_redirecTo"] = "Borrar Secciones"; //Acá se pone el nombre del sitio hacia donde se va a redireccionar
		$tipos_usuarios_permitidos = array();
		$tipos_usuarios_permitidos[0] = TIPO_USR_COORDINADOR;
		$this->cargarMsjLogueado($datos_plantilla, $tipos_usuarios_permitidos);

	}


/**
* Se realiza la asiganción de una sección a los datos que corrresponde a la seccionn seleccionada
* primero se realiza la rutina de comprobacion de usuaraio con la sesión iniciado
* luego se defienen como vacios los datos de la vista 
* se indiaca el valor del meni lateral que debe permanecer abierto
* Se limita el acceso solo a los coordinadores
* Se carga el modelo de secciones 
* Se realiza la operación de asiganción a la seccion correspondiente llamando al modelo
**/	
	public function asignarAsecciones()
	{
		$rut = $this->session->userdata('rut'); //Se comprueba si el usuario tiene sesi?n iniciada
		if ($rut == FALSE) {
			redirect('/Login/', ''); //Se redirecciona a login si no tiene sesi?n iniciada
		}



		$datos_vista = 0;		
		$subMenuLateralAbierto = "asignarAseccion"; 
		$muestraBarraProgreso = FALSE; //Indica si se muestra la barra que dice anterior - siguiente
		$tipos_usuarios_permitidos = array();
		$tipos_usuarios_permitidos[0] = TIPO_USR_COORDINADOR;
		$this->load->model('Model_secciones');
		$cod_seccion = $this->input->post("cod_seccion");
        $datos_vista = array('seccion' =>$this->Model_secciones->VerSeccionesNoAsignadas(), 'modulos' => $this->Model_secciones->verModulosPorAsignar(), 'salas' => $this->Model_secciones->verSalasPorAsignar());
		$this->cargarTodo("Secciones", 'cuerpo_secciones_asignar', "barra_lateral_secciones", $datos_vista, $tipos_usuarios_permitidos, $subMenuLateralAbierto, $muestraBarraProgreso);



	}

	/**
* Recibe los datos de la vista para hacer la asignación de secciones
*
* Se carga el modelo de secciones, donde se encuentra la función que realiza la asignación
* Se capturan las variables enviadas por POST desde la vista
* Se le dan los valores a la función y lo que retorna se guarda en confirmación
* esto se le envía a la vista para dar feedback al usuario
* Finalmente se carga toda la vista nuevamente en asignarAsecciones
*
**/

	public function HacerAsignarAsecciones()
	{
		$rut = $this->session->userdata('rut'); //Se comprueba si el usuario tiene sesi?n iniciada
		if ($rut == FALSE) {
			redirect('/Login/', ''); //Se redirecciona a login si no tiene sesi?n iniciada
		}

		$this->load->model('Model_secciones');

		$cod_seccion = $this->input->post('seccion_seleccionada');
		$cod_profesor = $this->input->post('profesor_seleccionado');
		$cod_modulo = $this->input->post('modulo_seleccionado');
		$cod_sala = $this->input->post('sala_seleccionada');
		$nombre_dia = $this->input->post('dia_seleccionado');
		$numero_modulo = $this->input->post('bloque_seleccionado');

		$confirmacion = $this->Model_secciones->AsignarSeccion($cod_seccion,$cod_profesor,$cod_modulo,$cod_sala,$nombre_dia,$numero_modulo);

        if ($confirmacion==1){
			$datos_plantilla["titulo_msj"] = "Acción Realizada";
			$datos_plantilla["cuerpo_msj"] = "Se ha asignado la sección con éxito";
			$datos_plantilla["tipo_msj"] = "alert-success";
		}
		else{
			$datos_plantilla["titulo_msj"] = "Acción No Realizada";
			$datos_plantilla["cuerpo_msj"] = "Ha ocurrido un error en la asignación de secciones";
			$datos_plantilla["tipo_msj"] = "alert-error";	
		}

		$datos_plantilla["redirectAuto"] = TRUE; //Esto indica si por javascript se va a redireccionar luego de 5 segundos
		$datos_plantilla["redirecTo"] = "Secciones/asignarAsecciones"; //Acá se pone el controlador/metodo hacia donde se redireccionará
		//$datos_plantilla["redirecFrom"] = "Login/olvidoPass"; //Acá se pone el controlador/metodo desde donde se llegó acá, no hago esto si no quiero que el usuario vuelva
		$datos_plantilla["nombre_redirecTo"] = "Realizar Asignación"; //Acá se pone el nombre del sitio hacia donde se va a redireccionar
		$tipos_usuarios_permitidos = array();
		$tipos_usuarios_permitidos[0] = TIPO_USR_COORDINADOR;
		$this->cargarMsjLogueado($datos_plantilla, $tipos_usuarios_permitidos);



	}

	/**
	* Se eliminnan la asignaciones de una sección determinada 
	* primero se realiza la rutina de comprobacion de usuaraio con la sesión iniciado
	* luego se defienen como vacios los datos de la vista 
	* se indiaca el valor del meni lateral que debe permanecer abierto
	* Se limita el acceso solo a los coordinadores
	* Se carga el modelo de secciones 
	* Se realiza la operación de  elimnar asignacionae invocando la función en el modelo	
	**/

	public function borrarAsignacion()
	{
		
		$datos_vista = 0;		
		$subMenuLateralAbierto = "borrarAsignar"; 
		$muestraBarraProgreso = FALSE; //Indica si se muestra la barra que dice anterior - siguiente
		$tipos_usuarios_permitidos = array();
		$tipos_usuarios_permitidos[0] = TIPO_USR_COORDINADOR;
		
		$this->load->model('Model_secciones');
		$cod_seccion = $this->input->post("cod_seccion");
        $datos_vista = array('seccion' =>$this->Model_secciones->VerSeccionesAsignadas());
		$this->cargarTodo("Secciones", 'cuerpo_secciones_eliminarAsignacion', "barra_lateral_secciones", $datos_vista, $tipos_usuarios_permitidos, $subMenuLateralAbierto, $muestraBarraProgreso);



	}


	/**
	* Función llamada por una vista a través de una petición AJAX
	* Esta función rescata, a través de la vista, la variable 'sección'
	* Dicha variable se guarda en otra de tipo local para llamar a otra función, en el modelo
	* Finalmente, el resultado de la función en el modelo se le convierte en su representacion JSON
	* y se le envía como un string a la vista
	*/

	public function postDetallesSeccion() {
		//Se comprueba que quien hace esta petición de ajax esté logueado
		if (!$this->isLogged()) {
			//echo 'No estás logueado!!';
			return;
		}

		$cod_seccion = $this->input->post('seccion');
		$this->load->model('Model_secciones');
		$resultado = $this->Model_secciones->getDetallesSeccion($cod_seccion);
		echo json_encode($resultado);
	}

	/**
	* Función llamada por una vista a través de una petición AJAX
	* Esta función rescata, a través de la vista, la variable 'textoFiltroBasico'
	* Dicha variable se guarda en otra de tipo local para llamar a otra función, en el modelo
	* Luego, se almacena la busqueda realizada para obtenerla, en caso de que el usuario desee repetirla
	* Finalmente, el resultado de la función en el modelo se le convierte en su representacion JSON
	* y se le envía como un string a la vista
	*/

	public function postBusquedaSecciones() {
		if (!$this->isLogged()) {
			//echo 'No estás logueado!!';
			return;
		}
		$textoFiltro = $this->input->post('textoFiltroBasico');
		$this->load->model('model_secciones');
		$resultado = $this->model_secciones->getSeccionesByFilter($textoFiltro);
		
		/* ACÁ SE ALMACENA LA BÚSQUEDA REALIZADA POR EL USUARIO */
		if (count($resultado) > 0) {
			$this->load->model('model_busquedas');
			//Se debe insertar sólo si se encontraron resultados
			$this->model_busquedas->insertarNuevaBusqueda($textoFiltro, 'secciones', $this->session->userdata('rut'));
		}
		echo json_encode($resultado);
	}

	/**
	* Se eliminnan la asignaciones de una sección determinada 
	* primero se realiza la rutina de comprobacion de usuaraio con la sesión iniciado
	* luego se defienen como vacios los datos de la vista 
	* se indiaca el valor del meni lateral que debe permanecer abierto
	* Se limita el acceso solo a los coordinadores
	* Se carga el modelo de secciones 
	* Se realiza la operación de  elimnar asignacionae invocando la función en el modelo	
	**/

	public function eliminarAsignacion()
	{

		$this->load->model('Model_secciones');
		$cod_seccion = $this->input->post('cod_seccion');

		$confirmacion = $this->Model_secciones->EliminarAsignacion($cod_seccion);

		if ($confirmacion==1){
			// mostramos el mensaje de operacion realizada
			$datos_plantilla["titulo_msj"] = "Acción Realizada";
			$datos_plantilla["cuerpo_msj"] = "Se ha eliminado la asignación de la sección";
			$datos_plantilla["tipo_msj"] = "alert-success";
		}
		else{
			$datos_plantilla["titulo_msj"] = "Acción No Realizada";
			$datos_plantilla["cuerpo_msj"] = "Ha ocurrido un error con la eliminación en base de datos";
			$datos_plantilla["tipo_msj"] = "alert-success";
		}
		
		
		$datos_plantilla["redirectAuto"] = FALSE; //Esto indica si por javascript se va a redireccionar luego de 5 segundos
		$datos_plantilla["redirecTo"] = "Secciones/borrarAsignacion"; //Acá se pone el controlador/metodo hacia donde se redireccionará
		$datos_plantilla["nombre_redirecTo"] = "Eliminar Asignación"; //Acá se pone el nombre del sitio hacia donde se va a redireccionar
		$tipos_usuarios_permitidos = array();
		$tipos_usuarios_permitidos[0] = TIPO_USR_COORDINADOR;
		$this->cargarMsjLogueado($datos_plantilla, $tipos_usuarios_permitidos);


	}

	/**
	* Función llamada por una vista a través de una petición AJAX
	* Esta función rescata, a través de la vista, la variable 'nombre_modulo'
	* Dicha variable se guarda en otra de tipo local para llamar a otra función, en el modelo
	* Finalmente, el resultado de la función en el modelo se le convierte en su representacion JSON
	* y se le envía como un string a la vista
	*/

	public function postDetalleModulos() {
		//Se comprueba que quien hace esta petición de ajax esté logueado
		if (!$this->isLogged()) {
			//echo 'No estás logueado!!';
			return;
		}
		$nombre_modulo = $this->input->post('nombre_modulo');
		$this->load->model('Model_secciones');
		$resultado = $this->Model_secciones->verProfeSegunModulo($nombre_modulo);
		echo json_encode($resultado);
	}

/**
	* Función llamada por una vista a través de una petición AJAX
	* Esta función rescata, a través de la vista, la variable 'sección'
	* Dicha variable se guarda en otra de tipo local para llamar a otra función, en el modelo
	* Finalmente, el resultado de la función en el modelo se le convierte en su representacion JSON
	* y se le envía como un string a la vista
	*/

	public function postDetalleUnaSeccion() {
		//Se comprueba que quien hace esta petición de ajax esté logueado
		if (!$this->isLogged()) {
			//echo 'No estás logueado!!';
			return;
		}

		$cod_seccion = $this->input->post('seccion');
		$this->load->model('Model_secciones');
		$resultado = $this->Model_secciones->getDetalleUnaSeccion($cod_seccion);
		echo json_encode($resultado);
	}

/**
	* Función llamada por una vista a través de una petición AJAX
	* Esta función rescata, a través de la vista, las variable 'dia_post' y 'bloque_post'
	* Dichas variables se guardan en otras de tipo local para llamar a otra función, en el modelo
	* Finalmente, el resultado de la función en el modelo se le convierte en su representacion JSON
	* y se le envía como un string a la vista
	*/

	public function postVerificaHorarios() {
		//Se comprueba que quien hace esta petición de ajax esté logueado
		if (!$this->isLogged()) {
			//echo 'No estás logueado!!';
			return;
		}
		$dia = $this->input->post('dia_post');
		$bloque = $this->input->post('bloque_post');
		$this->load->model('Model_secciones');
		$resultado = $this->Model_secciones->getVerificaHorarios($dia, $bloque);
		echo json_encode($resultado);
	}
		
}
