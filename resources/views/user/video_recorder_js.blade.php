<script type="text/javascript">
    //variables
    // const $dispositivosDeAudio = document.querySelector("#dispositivosDeAudio"),
    // $dispositivosDeVideo = document.querySelector("#dispositivosDeVideo"),
    const $duracion = document.querySelector("#duracion"),
    $video = document.querySelector("#videoPreview"),
    $btnComenzarGrabacion = document.querySelector("#btnComenzarGrabacion"),
    $btnDetenerGrabacion = document.querySelector("#btnDetenerGrabacion"),
    $btnReiniciarGrabacion = document.querySelector("#btnReiniciarGrabacion");
    $dropContent = document.querySelector(".dropsContent");
    
    let tiempoInicio, mediaRecorder, idIntervalo;
    let blobVideo;
    var inicio;
    var videoMP4;
    var d, nameFile;
    let urlParaDescargar;
    let audioInputId, videoInputId;
    let idConteoRegresivo;

    let datos = new FormData();

    const limpiarSelect = elemento => {
        for (let x = $(elemento).length - 1; x >= 1; x--) {
            elemento.options.remove(x);
        }
    }

    $('#newVideo').ready(() => {
        $('#videoUpload').hide();
    });


    $('.btn-new-video').on('click',() => {
        validarSoporte();
    });

    $('.close-modal').on('click',() => {
        $('#newVideo').hide();
    });

    function validarSoporte() {
        const tieneSoporteUserMedia = () => !!(navigator.mediaDevices.getUserMedia);

        if (typeof MediaRecorder === "undefined" || !tieneSoporteUserMedia()) {                
            alert('Browser does not have recording support');
        }
        else {            
            document.getElementById('timer').innerHTML = "00:00";
            document.getElementById('footer-timer').innerHTML = "00:00";
            $dropContent.style.background = "#ffffff7d";
            $video.srcObject = null;
            $('#newVideo').show();
            $('#btnComenzarGrabacion').show();          
            $('#btnDetenerGrabacion').hide();
            $('#btnGuardarGrabacion').hide();  
            $('#btnReiniciarGrabacion').hide();  
            // loadInput();
        }
    }

    function tiempo() {
        inicio = 0;
        var tiempoTranscurrido;
        idIntervalo = setInterval( () => {
            inicio++;
            
            if (inicio >= 0 && inicio < 10) {
                tiempoTranscurrido = "00:0"+inicio;
            }
            else if (inicio > 9 && inicio < 60) {
                tiempoTranscurrido = "00:"+inicio;
            }
            else if (inicio >= 60){
                tiempoTranscurrido = (inicio/60)+":00";
                $btnDetenerGrabacion.click();
            }
            document.getElementById('timer').innerHTML = tiempoTranscurrido;
            document.getElementById('footer-timer').innerHTML = tiempoTranscurrido;
        }, 1000, "javascript");
    }

    function conteoRegresivo() {
        document.getElementById('close-modal-new-video').disabled = true;
        var conteo = 3;
        idConteoRegresivo = setInterval( () => {
            if (conteo === 3) {                
                $('#btnComenzarGrabacion').hide();     
            }
            if (conteo === 0) {
                clearInterval(idConteoRegresivo);                 
                $('#btnDetenerGrabacion').show();             
                $dropContent.style.background = "#0000007d";
                $('#conteoRegresivo').hide();
            }
            if (conteo === 2) {
                comenzarAGrabar();
            }
            document.getElementById('conteoRegresivo').innerHTML = conteo;
            conteo--;
        }, 1000, "javascript");
    }

    function comenzarAGrabar() {
        // if (!$("#dispositivosDeAudio > *").length) return alert("No hay micrófono");
        // if (!$("#dispositivosDeVideo > *").length) return alert("No hay cámara");
        // No permitir que se grabe doblemente
        if (mediaRecorder) return alert("Ya se está grabando");

        navigator.mediaDevices.getUserMedia({
            audio: {
                deviceId: audioInputId, //$dispositivosDeAudio.value, // Indicar dispositivo de audio
            },
            video: {
                deviceId: videoInputId, //$dispositivosDeAudio.value, // Indicar dispositivo de vídeo
            }
        })
        .then(stream => {
            $('#alertWebcam').hide();
            // Poner stream en vídeo          
            document.getElementById('close-modal-new-video').disabled = true;
            tiempo();
            $video.srcObject = stream;
            $video.play();
            // Comenzar a grabar con el stream
            mediaRecorder = new MediaRecorder(stream);
            mediaRecorder.start();
            // En el arreglo pondremos los datos que traiga el evento dataavailable
            const fragmentosDeAudio = [];
            // Escuchar cuando haya datos disponibles
            mediaRecorder.addEventListener("dataavailable", evento => {
                // Y agregarlos a los fragmentos
                fragmentosDeAudio.push(evento.data);
            });
            // Cuando se detenga (haciendo click en el botón) se ejecuta esto
            mediaRecorder.addEventListener("stop", () => {    
                $('#conteoRegresivo').text = "OK"; 
                $('#btnDetenerGrabacion').hide();               
                if (inicio >=30) {
                    $('#btnGuardarGrabacion').show();
                } 
                $('#btnReiniciarGrabacion').show();  
                // Pausar vídeo
                $video.pause();
                // Detener el stream
                stream.getTracks().forEach(track => track.stop());
                // Detener la cuenta regresiva
                // Convertir los fragmentos a un objeto binario
                blobVideo = new Blob(fragmentosDeAudio, {'type': 'video/mp4'});
                datos.append('video', blobVideo, 'video');
                
                // urlParaDescargar = URL.createObjectURL(blobVideo);
            });

            mediaRecorder.addEventListener("save", () => {
                //Debe mandar a guardar el blob a traves del llamada a la ruta postvideo
            });

            mediaRecorder.addEventListener("restart", ()=> {
                //Reiniciar la grabacion
            });
        })
        .catch(error => {
            // Aquí maneja el error, tal vez no dieron permiso
            // videos usuarios
            alert(`{{__("It does not record, please, verify you allow your browser to use your camera and microphone")}}`);
            $('#alertWebcam').show();
            clearInterval(idConteoRegresivo);
            $('#close-modal-new-video').click(); 
        });
    };

    const detenerGrabacion = () => {
        if (!mediaRecorder) return;
        mediaRecorder.stop();
        mediaRecorder = null;
        clearInterval(idIntervalo);
        document.getElementById('close-modal-new-video').disabled = false;   
    };

    function guardarGrabacion() {         
        $('#btnGuardarGrabacion').hide();       
        $('#btnReiniciarGrabacion').hide();  
        $('#close-modal-new-video').prop('disabled', true); 
        $('#setVideoName').show(); 
    }

    function eliminarVideo(id) {
        AWS.config.update({            
            // useAccelerateEndpoint: true,
            accessKeyId : ' AKIAQYEJSJ2ZH2ZFRBXG',
            secretAccessKey : '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'
        });
        AWS.config.region = 'us-east-2';
        var bucket = new AWS.S3();
        
        $.ajax({
            type: 'GET',
            url: "{{ route('delete.video.apply') }}",
            data: {
                video_id: id
            },
            success: function(data) {

                var params = {
                    Bucket: 'filescloik/videos',
                    Key: decodeURIComponent(data)
                };
                bucket.deleteObject(params, function(err, data) {
                    if (data) {
                        var url = {!! json_encode(Request::url()) !!};
                        window.location.href = url;
                    }
                });
                
            }
        });
        
    }


    function mostrarNotificacion() { 
        var conteo = 3;           
        $('#notification').show(); 
        var idConteoRegresivo = setInterval( () => {
            if (conteo === 0) {
                clearInterval(idConteoRegresivo);
                $('#notification').hide(); 
            }
            conteo--;
        }, 1000, "javascript");     
    }

    const reiniciarGrabacion = () => {
        $dropContent.style.background = "#ffffff7d";
        $video.srcObject = null;
        $('#btnComenzarGrabacion').show();         
        $('#btnDetenerGrabacion').hide();
        $('#btnGuardarGrabacion').hide();  
        $('#btnReiniciarGrabacion').hide();
        document.getElementById('conteoRegresivo').innerHTML = "OK";
        $('#conteoRegresivo').show();
        document.getElementById('timer').innerHTML = "00:00";
        document.getElementById('footer-timer').innerHTML = "00:00";
    };


    $('#btnComenzarGrabacion').on('click', () => {
        conteoRegresivo();
    });
    
    $btnDetenerGrabacion.addEventListener("click", detenerGrabacion);    
    $btnReiniciarGrabacion.addEventListener("click", reiniciarGrabacion);

    $('#btnGuardarGrabacion').on('click', function() {
        guardarGrabacion();
    });

    $('#close-modal-new-video').on('click',function(){
        if(!idConteoRegresivo==null){clearInterval(idConteoRegresivo)};
        detenerGrabacion();
        reiniciarGrabacion();
        document.getElementById('close-modal-new-video').disabled = false;
    });


    $(document).ready(() => {
        // $('.menu_options').hide();
        // $('.menu_options2').hide();                
        $('#notification').hide();               
        $('#setVideoName').hide();  
    });

    $('#saveVideoName').on('click',() => {
        var name = $('#videoName').val();

        if (name.length > 0) {
            $('#videoName').removeClass("nameRequired");                          
            $('#setVideoName').hide(); 

            d = name;
            guardarVideo();
        }
        else {
            $('#videoName').addClass("nameRequired");
        }
    });

    $('#closeVideoName').on('click', () => {
        
        $('#btnGuardarGrabacion').show();       
        $('#btnReiniciarGrabacion').show();  
        $('#close-modal-new-video').prop('disabled', false); 
        $('#videoName').val('');
        $('#setVideoName').hide(); 
    });

    function guardarVideo() {
        
        $('#videoUpload').show();  
        var date = new Date();
        var userid = "{{Auth::user()->id}}";        
        var marcaTiempo = 'U' + userid + 'T' + date.getTime();
        var d2 = d;

        d2 = d2.replace(/[^a-zA-Z0-9]/g, '');
        d2 = d2.replace(' ', '');
        var nameFile =marcaTiempo + d + '.mp4';
        var fileKey = marcaTiempo +'Name'+ d2 + '.mp4';
        // blobVideo.name = nameFile;
        
        var file = blobVideo;
        if (file) {
            
            var body = {
                Key: nameFile,
                ContentType: file.type,
                Body: file
            };
            AWS.config.update({            
                // useAccelerateEndpoint: true,
                accessKeyId : ' AKIAQYEJSJ2ZH2ZFRBXG',
                secretAccessKey : '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'
            });
            AWS.config.region = 'us-east-2';
            var bucket = new AWS.S3(
                {
                    partSize: 5 * 1024 * 1024,
                    queueSize: 1,
                    params: {
                        Bucket: 'filescloik/videos',
                    }
            });
            bucket.upload(body).on('httpUploadProgress', (evt) => {})
            .send(function(err, data) {
                if (data) {
                    $.ajax({
                        type: 'GET',
                        url: "{{ route('post.video.apply') }}",
                        data: {
                            name: d,
                            is_main: 0,
                            marca: marcaTiempo
                        },
                        success: function() {                        
                            $('#videoUpload').hide();
                            $('.close-modal').trigger('click');
                            mostrarNotificacion();
                            var url = {!! json_encode(Request::url()) !!};
                            window.location.href = url;
                        }
                    });
                    
                }

                if (err) {
                    alert("Error upload");
                    $('#videoUpload').hide();
                    console.log(err);
                }
            });
        }
    }
</script>    