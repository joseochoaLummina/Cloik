@push('scripts')
    <!-- <script src="{{ asset('js/bconverter.min.js')}}"></script> -->
@endpush

<script type="text/javascript">

const init = () => {
    window.URL = window.URL || window.webkitURL;
    let audioBlob;
    let blobAudio;
    var audioCtx = new (AudioContext || webkitAudioContext)();
    
    const tieneSoporteUserMedia = () =>
        !!(navigator.mediaDevices.getUserMedia)

    // Si no soporta...
    // Amable aviso para que el mundo comience a usar navegadores decentes ;)
    if (typeof MediaRecorder === "undefined" || !tieneSoporteUserMedia())
        return alert("Tu navegador web no cumple los requisitos; por favor, actualiza a un navegador como Firefox o Google Chrome");


    // Declaración de elementos del DOM
    const $listaDeDispositivos = document.querySelector("#listaDeDispositivos"),
        $duracion = document.querySelector("#duracion"),
        $btnComenzarGrabacion = document.querySelector("#btnComenzarGrabacion"),
        $btnDetenerGrabacion = document.querySelector("#btnDetenerGrabacion"),
        $btnEnviarAudio = document.querySelector("#btnEnviarAudio");

    // Algunas funciones útiles
    const limpiarSelect = () => {
        for (let x = $listaDeDispositivos.options.length - 1; x >= 0; x--) {
            $listaDeDispositivos.options.remove(x);
        }
    }

    const segundosATiempo = numeroDeSegundos => {
        let horas = Math.floor(numeroDeSegundos / 60 / 60);
        numeroDeSegundos -= horas * 60 * 60;
        let minutos = Math.floor(numeroDeSegundos / 60);
        numeroDeSegundos -= minutos * 60;
        numeroDeSegundos = parseInt(numeroDeSegundos);
        if (horas < 10) horas = "0" + horas;
        if (minutos < 10) minutos = "0" + minutos;
        if (numeroDeSegundos < 10) numeroDeSegundos = "0" + numeroDeSegundos;

        return `${horas}:${minutos}:${numeroDeSegundos}`;
    };
    // Variables "globales"
    let tiempoInicio, mediaRecorder, idIntervalo;
    const refrescar = () => {
            $duracion.textContent = segundosATiempo((Date.now() - tiempoInicio) / 1000);
        }
        // Consulta la lista de dispositivos de entrada de audio y llena el select
    const llenarLista = () => {
        navigator
            .mediaDevices
            .enumerateDevices()
            .then(dispositivos => {
                limpiarSelect();
                dispositivos.forEach((dispositivo, indice) => {
                    if (dispositivo.kind === "audioinput") {
                        const $opcion = document.createElement("option");
                        // Firefox no trae nada con label, que viva la privacidad
                        // y que muera la compatibilidad
                        $opcion.text = dispositivo.label || `Dispositivo ${indice + 1}`;
                        $opcion.value = dispositivo.deviceId;
                        $listaDeDispositivos.appendChild($opcion);
                    }
                })
            })
    };
    // Ayudante para la duración; no ayuda en nada pero muestra algo informativo
    const comenzarAContar = () => {
        $("#btnComenzarGrabacion").hide();
        $("#btnDetenerGrabacion").show();
        tiempoInicio = Date.now();
        idIntervalo = setInterval(refrescar, 500);
    };

    // Comienza a grabar el audio con el dispositivo seleccionado
    const comenzarAGrabar = () => {
        if (!$listaDeDispositivos.options.length) return alert("No hay dispositivos");
        // No permitir que se grabe doblemente
        if (mediaRecorder) return alert("Ya se está grabando");

        
        var es_chrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;

        var options = {
            audioBitsPerSecond : 16000
        }

        var audioConfig;

        if (es_chrome) {
            audioConfig = {
                    deviceId: $listaDeDispositivos.value,
                    sampleSize: 16,
                    channelCount: 1,
                    sampleRate: 16000
                }
        }
        else {
            audioConfig = {
                    deviceId: $listaDeDispositivos.value,
                }
        }
        navigator.mediaDevices.getUserMedia({
                audio: audioConfig
            })
            .then(
                stream => {
                    // Comenzar a grabar con el stream
                    if (es_chrome) {
                        mediaRecorder = new MediaRecorder(stream, options);
                    }
                    else {
                        mediaRecorder = new MediaRecorder(stream);
                    }
                    mediaRecorder.start();
                    comenzarAContar();
                    // En el arreglo pondremos los datos que traiga el evento dataavailable
                    const fragmentosDeAudio = [];
                    // Escuchar cuando haya datos disponibles
                    mediaRecorder.addEventListener("dataavailable", evento => {
                        // Y agregarlos a los fragmentos
                        fragmentosDeAudio.push(evento.data);
                    });
                    // Cuando se detenga (haciendo click en el botón) se ejecuta esto
                    mediaRecorder.addEventListener("stop", () => {
                        // Detener el stream
                        stream.getTracks().forEach(track => track.stop());
                        // Detener la cuenta regresiva
                        detenerConteo();
                        if (es_chrome) {
                            blobAudio = new Blob(fragmentosDeAudio, {type: 'audio/mpeg'});
                            audioBlob = blobAudio;
                        }
                        else {
                            blobAudio = new Blob(fragmentosDeAudio, {type: 'audio/mpeg'});
                            audioBlob = blobAudio;                            
                        }

                        const urlParaDescargar = window.URL.createObjectURL(blobAudio);
                        cargarAudio(urlParaDescargar);
                        // Crear un elemento <a> invisible para descargar el audio
                        // let a = document.createElement("a");
                        // document.body.appendChild(a);
                        // a.style = "display: none";
                        // a.href = urlParaDescargar;
                        // a.download = "grabacion.mp3";
                        // // Hacer click en el enlace
                        // a.click();
                        // // Y remover el objeto
                        // window.URL.revokeObjectURL(urlParaDescargar);
                    });
                }
            )
            .catch(error => {
                // Aquí maneja el error, tal vez no dieron permiso
                alert(`{{__("It does not record, please, verify you allow your browser to use your microphone")}}`);
                location.reload(); 
            });
    };
    
    const cargarAudio = urlParaDescargar =>{
        $("#audioTest").show();
        $("#audioTest").html(
            `<audio id="testPreviwAudio" style="width: 100%;" controls controlsList="download">
                <source src='${urlParaDescargar}' type='audio/mpeg'>
                <source src='${urlParaDescargar}' type='audio/ogg'>
                    Your browser does not support the audio element.
            </audio>`
        );
    }

    function blobToFile(myfile, nombre) {
        myfile.name = nombre;
        myfile.lastModified = new Date();
        return myfile;
    }

    const enviarAudio = () => {
        var date = new Date();
        var es_chrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
        var userid = "{{Auth::user()->id}}";        
        var marcaTiempo = 'U' + userid + 'T' + date.getTime();
        var nameFile = 'TestLanguage.wav';
        var fullName = (marcaTiempo + nameFile);
        
        if (es_chrome) {
            subirBlobAwsS3Chrome(audioBlob, fullName);
        }
        else {
            fileToSend = blobToFile(audioBlob, fullName);
            ConvertirAudio(fileToSend, fullName);
        }    
        
    }

    const detenerConteo = () => {
        $("#btnDetenerGrabacion").hide();
        $("#btnComenzarGrabacion").show();
        $("#btnEnviarAudio").prop( "disabled", false ); //Enable
        clearInterval(idIntervalo);
        tiempoInicio = null;
        $duracion.textContent = "";
    }

    const detenerGrabacion = () => {
        if (!mediaRecorder) return alert(`{{__("It does not record, please, verify you allow your browser to use your microphone")}}`);
        mediaRecorder.stop();
        mediaRecorder = null;
    };


    $btnComenzarGrabacion.addEventListener("click", comenzarAGrabar);
    $btnDetenerGrabacion.addEventListener("click", detenerGrabacion);
    $btnEnviarAudio.addEventListener("click", enviarAudio);

    // Cuando ya hemos configurado lo necesario allá arriba llenamos la lista

    llenarLista();
}


function outputInfo(e){
    document.getElementById("outputArea").innerHTML=e
}

function timeToSeconds(e){
    var t=e.split(":");
    return 60*parseFloat(t[0])*60+60*parseFloat(t[1])+parseFloat(t[2])+parseFloat("0."+t[3])
}

function audio_convert(e,t,o,n,a,s,i,p,u,c,r,l){
    var d, arguments,
        f="input."+c.split(".").pop(),
        m=/Duration: (.*?), /,
        h=/time=(.*?) /,
        w=new Worker("{{asset('/')}}/js/auido_worker.js");

    switch(w.onmessage = function(e){
        var t=e.data;
        if("ready" === t.type&&window.File&&window.FileList&&window.FileReader);
        else if("stdout"==t.type)
            console.log(t.data);
        else if("stderr"==t.type){
            if(console.log(t.data),m.exec(t.data)&&(d=timeToSeconds(m.exec(t.data)[1])),h.exec(t.data)){var o=timeToSeconds(h.exec(t.data)[1]);
            ;d&&r(Math.floor(o/d*100))}
        }
        else if("done"==t.type){
            var n=t.data.code,a=Object.keys(t.data.outputFiles);
            if(0==n&&a.length>0&&t.data.outputFiles[a[0]].byteLength>0){
                var s=a[0], i=t.data.outputFiles[s];
                 l(i,s)
            }
            else 
                l(null)
        }
    },
    (arguments=[]).push("-i"),
    arguments.push(f),    
    ""!=t&&(arguments.push(s),arguments.push(t)),
    ""!=o&&(arguments.push(i),arguments.push(o)),
    ""!=n&&(arguments.push(p),arguments.push(n)),
    a.toLowerCase()){
        case"mp3":arguments.push("-acodec"),arguments.push("libmp3lame"),arguments.push("output(js-audio-converter.com).mp3");
        break;
        case"ogg":arguments.push("-acodec"),""!=u?(arguments.push(u),"vorbis"==u&&(arguments.push("-strict"),arguments.push("-2"))):arguments.push("flac"),arguments.push("output(js-audio-converter.com).ogg");
        break;
        case"aac":arguments.push("-acodec"),""!=u?arguments.push(u):arguments.push("aac"),arguments.push("-f"),arguments.push("mp4"),arguments.push("output(js-audio-converter.com).aac");
        break;
        case"wma":arguments.push("-acodec"),""!=u?arguments.push(u):arguments.push("wmav1"),arguments.push("-f"),arguments.push("asf"),arguments.push("output(js-audio-converter.com).wma");
        break;
        case"wav":arguments.push("output(js-audio-converter.com).wav");
        break;
        case"m4a":arguments.push("-acodec"),""!=u?arguments.push(u):arguments.push("aac"),arguments.push("output(js-audio-converter.com).m4a");
        break;
        case"m4r":arguments.push("-acodec"),arguments.push("aac"),arguments.push("-f"),arguments.push("ipod"),arguments.push("output(js-audio-converter.com).m4r");
        break;
        case"flac":arguments.push("-acodec"),arguments.push("flac"),arguments.push("output(js-audio-converter.com).flac");
        break;
        case"opus":arguments.push("-acodec"),arguments.push("libopus"),arguments.push("output(js-audio-converter.com).opus");
        break;
        case"aiff":arguments.push("-acodec"),arguments.push("pcm_s16be"),arguments.push("output(js-audio-converter.com).aiff");
        break;
        case"mmf":arguments.push("-acodec"),arguments.push("adpcm_yamaha"),arguments.push("-strict"),arguments.push("-2"),arguments.push("output(js-audio-converter.com).mmf")
        }w.postMessage({
            type:"command",arguments:arguments,files:[{name:f,data:e}]
        })
}

function ConvertirAudio(audioMP3, audioName) {
    var es_chrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
    window.File&&window.FileReader&&window.FileList&&window.Blob||outputInfo(str_browserNotSupport);

    var t = audioMP3;

    var t = new FileReader;
    t.onload = function (e) {
        var t = this.result;
        array = new Uint8Array(t), function(e) {
            var o = '', n = "/convertParam.htmlx1=128k&x2=44100&x3=2&x4=WAV&x5=pcm_s16le";

            if (200===200) {
                var t = ""; // t='128k,44100,2,-ab,-ar,-ac,WAV,pcm_s16le'
                
                t='128k,44100,2,-ab,-ar,-ac,WAV,pcm_s16le';
                audio_convert(e, '16k', '16000', '1', 'WAV', '-ab','-ar','-ac','pcm_s16le',audioName, 
                    function(e) {},
                    function(e,t) {
                        if (e) {
                            var o=new Blob([e], {type: 'audio/wav;codecs=pcm_s16le'});                            
                            n = window.URL.createObjectURL(o);
                            // console.clear();
                            subirBlobAwsS3(o, audioName);
                        }
                    }
                );
            }
            else {
                console.log("Request failed.  Returned status of "+o.status), o
            }
        }(array)
    }, t.readAsArrayBuffer(audioMP3);
    
}

function subirBlobAwsS3Chrome(file, audioName) {
    if (file) {
        $("#add_language_test_modal").modal('hide');
        $("#add_language_test_thanks_modal").modal({backdrop: 'static', keyboard: false});
        document.getElementById('add_language_test_thanks_modal').innerHTML='<div class="modal-dialog"> <!-- Modal content--><div class="modal-content"><div class="modal-body"><div class="row"><div class="col-md-12"><div class="userccount"><h1>{{__("Evaluating test, please wait")}}</h1></div></div></div></div></div></div>';
        var body = {
            Key: audioName,
            ContentType: file.type,
            Body: file
        };

        AWS.config.update({                    
            accessKeyId : 'AKIAQYEJSJ2ZH2ZFRBXG',
            secretAccessKey : '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'
        });

        AWS.config.region = 'us-east-2';

        var bucket = new AWS.S3(
        {
            partSize: 5 * 1024 * 1024,
            queueSize: 1,
            params: {
                Bucket: 'filescloik/temp_audios',
            }
        });

        bucket.upload(body).on('httpUploadProgress', (evt) => {})
        .send(function(err, data) {
            if (data) {
                var urlAudio = data["Location"];
                var textAudio = $("#test-paragraph").html();
                var iso_code = document.getElementById("iso_code").value;
                var api_code = document.getElementById("api_code").value;
                var id_text = document.getElementById("id_paragraph").value;

                $.ajax({
                    type: 'POST',
                    url: 'https://www.ccaapi.tk/convertwav',
                    dataType: 'json',
                    data: {
                        url: urlAudio
                    },
                    success: function(data) {
                        if (data.codigo == 200) {
                            if (data.datos.statusCode == 200) {
                                urlAudio = data.datos.msg;
                                $.ajax({
                                    type: 'POST',
                                    url: 'https://cloik-262019.appspot.com/api/v1/test-idiomas',
                                    dataType: 'json',
                                    headers: {
                                        'Authorization': 'Basic ' + btoa("tzapMZRnpm8FAcTOCyH1lYZ0raDamX1M:i3dSAXafIzycFbh9MwVxIgCasbiElLc1"),
                                    },
                                    data: {
                                        audio: urlAudio,
                                        lang: api_code,
                                        text: textAudio,
                                        await: true
                                    },
                                    success: function(data) {
                                        if (data.err == false) {
                                            $.ajax({
                                                type: "POST",
                                                url: "{{route('my.language.test')}}",
                                                data: {
                                                    "_token": "{{ csrf_token() }}",
                                                    lang: iso_code,
                                                    url: urlAudio,
                                                    score: data.word.match,
                                                    match: data.word.diff,
                                                    id_paragraph: id_text
                                                },
                                                datatype: 'json',
                                                success: (json) => {
                                                    $("#add_language_test_thanks_modal").html(json.html);

                                                    if ((json.url).length>0) {
                                                        var key = (json.url).replace("https://filescloik.s3.us-east-2.amazonaws.com/audios/", "");
                                                        eliminarVideo(key);
                                                    }
                                                },
                                                error: function(err2) {
                                                    console.log('err2: ' ,err2);
                                                }
                                            });
                                        }
                                        else {
                                            console.log(data);
                                        }
                                    },
                                    error: function(err) {
                                        console.log(err);
                                    }
                                });
                            }
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    },
		    timeout: 60000

                });

            }

            if (err) {
                alert("Error upload");
                // $('#videoUpload').hide();
                console.log(err);
            }
        });
    }
} 

function subirBlobAwsS3(file, audioName) {
    if (file) {
        $("#add_language_test_modal").modal('hide');
        $("#add_language_test_thanks_modal").modal();

        var body = {
            Key: audioName,
            ContentType: file.type,
            Body: file
        };

        AWS.config.update({                    
            accessKeyId : ' AKIAQYEJSJ2ZH2ZFRBXG',
            secretAccessKey : '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'
        });

        AWS.config.region = 'us-east-2';

        var bucket = new AWS.S3(
        {
            partSize: 5 * 1024 * 1024,
            queueSize: 1,
            params: {
                Bucket: 'filescloik/audios',
            }
        });

        bucket.upload(body).on('httpUploadProgress', (evt) => {})
        .send(function(err, data) {
            if (data) {
                var urlAudio = data["Location"];
                var textAudio = $("#test-paragraph").html();
                var iso_code = document.getElementById("iso_code").value;
                var api_code = document.getElementById("api_code").value;
                var id_text = document.getElementById("id_paragraph").value;

                $.ajax({
                    type: 'POST',
                    url: 'https://cloik-262019.appspot.com/api/v1/test-idiomas',
                    dataType: 'json',
                    headers: {
                        'Authorization': 'Basic ' + btoa("tzapMZRnpm8FAcTOCyH1lYZ0raDamX1M:i3dSAXafIzycFbh9MwVxIgCasbiElLc1"),
                    },
                    data: {
                        audio: urlAudio,
                        lang: api_code,
                        text: textAudio,
                        await: true
                    },
                    success: function(data) {
                        if (data.err == false) {
                            $.ajax({
                                type: "POST",
                                url: "{{route('my.language.test')}}",
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    lang: iso_code,
                                    url: urlAudio,
                                    score: data.word.match,
                                    match: data.word.diff,
                                    id_paragraph: id_text
                                },
                                datatype: 'json',
                                success: (json) => {
                                    $("#add_language_test_thanks_modal").html(json.html);

                                    /*if ((json.url).length>0) {
                                        var key = (json.url).replace("https://filescloik.s3.us-east-2.amazonaws.com/audios/", "");
                                        eliminarVideo(key);
                                    }*/
                                },
                                error: function(err2) {
                                    console.log('err2: ' ,err2);
                                }
                            });
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
            }

            if (err) {
                alert("Error upload");
                // $('#videoUpload').hide();
                console.log(err);
            }
        });
    }
} 

function eliminarVideo(url) {  
    AWS.config.update({            
        // useAccelerateEndpoint: true,
        accessKeyId : 'AKIAQYEJSJ2ZH2ZFRBXG',
        secretAccessKey : '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'
    });
    AWS.config.region = 'us-east-2';
    var bucket = new AWS.S3();

    var params = {
        Bucket: 'filescloik/audios',
        Key: decodeURIComponent(url)
    };
    bucket.deleteObject(params, function(err, data) {
        if (data) {
        }
    });

}
    // Esperar a que el documento esté listo...
document.addEventListener("DOMContentLoaded", init);
</script>
