document.addEventListener("DOMContentLoaded", function () {
    // 1. Levantamos los datos de la partida
    var datos = JSON.parse(sessionStorage.getItem("DatosPartidaTerminada"));
    var nombreJugador1 = sessionStorage.getItem("usuario1") || "Jugador 1";
    var nombreJugador2 = sessionStorage.getItem("usuario2") || "Jugador 2";

    if (datos) {
        // Enviar a la Base de Datos automáticamente vía AJAX (solo la primera vez)
        if (!sessionStorage.getItem("PartidaGuardadaBD")) {
            var peticion = new XMLHttpRequest();

            peticion.onreadystatechange = function () {
                if (peticion.readyState == 4 && peticion.status == 200) {
                    var data = JSON.parse(peticion.responseText);
                    if (data.exito) {
                        sessionStorage.setItem("PartidaGuardadaBD", "true");
                        console.log("Sincronización con BD completada:", data.mensaje);
                    } else {
                        console.error("Fallo de BD:", data.mensaje);
                    }
                }
            };

            var parametros = "datos=" + encodeURIComponent(JSON.stringify(datos));

            peticion.open("POST", "procesar_resultado.php", true);
            peticion.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            peticion.send(parametros);
        }

        let p = datos.tabla_partida;
        let j1 = datos.tabla_usuario_partida.jugador1;
        let j2 = datos.tabla_usuario_partida.jugador2;

        let totalPares = p.dificultad / 2;
        let minJugados = Math.floor(p.tiempo_jugado / 60);
        let secJugados = p.tiempo_jugado % 60;

        // Inyectamos el Tiempo y Parámetros Globales
        document.getElementById("datosPartida").innerHTML = `
            <p><strong>Tiempo invertido:</strong> ${minJugados}m ${secJugados}s</p>
            <p><strong>Dificultad base:</strong> ${p.dificultad} Cartas (${totalPares} Pares)</p>
        `;

        // Motor evaluador de los mensajes de desempeño
        function clasificarDesempeno(aciertos, idRes, oponenteAbandono) {
            let porcentaje = (aciertos / totalPares) * 100;

            // idRes: 1 = Ganó, 2 = Perdió, 3 = Empató, 4 = Abandonó
            let gano = (idRes === 1 || idRes === 3);

            if (idRes === 4) {
                return "Abandonaste y perdiste la partida. Debes practicar más tu tolerancia.";
            }

            if (oponenteAbandono && idRes === 1) {
                return "¡Ganaste por abandono del oponente!";
            }

            if (gano) {
                if (porcentaje === 100) return "¡¡¡EXCELENTE MEMORIA!!!";
                if (porcentaje >= 80 && porcentaje <= 99) return "¡¡¡MUY BUENA MEMORIA!!!";
                if (porcentaje >= 60 && porcentaje <= 79) return "¡¡¡BUENA MEMORIA!!! ¡¡¡Puedes mejorar!!!";
                if (porcentaje < 60) return "¡¡¡Ganaste, pero necesitas entrenar más tu memoria!!!";
            } else {
                // Perdió
                if (porcentaje >= 80 && porcentaje <= 99) return "¡¡¡MUY BUENA MEMORIA!!!";
                if (porcentaje >= 60 && porcentaje <= 79) return "¡¡¡BUENA MEMORIA!!! ¡¡¡Puedes mejorar!!!";
                if (porcentaje < 60) return "¡¡¡Mala memoria, debes practicar más!!!";
            }
            return "¡Desempeño estándar!";
        }

        // Proyectamos información de J1
        let msj1 = clasificarDesempeno(j1.pares_descubiertos, j1.id_resultado, j2.id_resultado === 4);
        let ganoColor1 = (j1.id_resultado === 1 || j1.id_resultado === 3) ? '#22c55e' : '#ef4444';

        document.getElementById("resultadoU1").innerHTML = `
            <h2 class="subtitulo-juego" style="margin-bottom:20px;">${nombreJugador1}</h2>
            <div style="font-size:16px; color:#475569; margin-bottom:15px;">
                <p>Pares descubiertos: <strong style="color:#0f172a">${j1.pares_descubiertos}</strong></p>
                <p>Intentos gastados: <strong style="color:#0f172a">${j1.intentos}</strong></p>
                <p>Puntaje en tabla: <strong style="color:#0f172a">${j1.puntaje} pts</strong></p>
            </div>
            <p class="jugador-msj" style="font-weight: 700; color: ${ganoColor1}; border-top: 1px solid #cbd5e1; padding-top:15px;">${msj1}</p>
        `;

        // Proyectamos información de J2
        let msj2 = clasificarDesempeno(j2.pares_descubiertos, j2.id_resultado, j1.id_resultado === 4);
        let ganoColor2 = (j2.id_resultado === 1 || j2.id_resultado === 3) ? '#22c55e' : '#ef4444';

        document.getElementById("resultadoU2").innerHTML = `
            <h2 class="subtitulo-juego" style="margin-bottom:20px;">${nombreJugador2}</h2>
            <div style="font-size:16px; color:#475569; margin-bottom:15px;">
                <p>Pares descubiertos: <strong style="color:#0f172a">${j2.pares_descubiertos}</strong></p>
                <p>Intentos gastados: <strong style="color:#0f172a">${j2.intentos}</strong></p>
                <p>Puntaje en tabla: <strong style="color:#0f172a">${j2.puntaje} pts</strong></p>
            </div>
            <p class="jugador-msj" style="font-weight: 700; color: ${ganoColor2}; border-top: 1px solid #cbd5e1; padding-top:15px;">${msj2}</p>
        `;

    } else {
        document.getElementById("datosPartida").innerHTML = "<p>No hay datos de una partida terminada. Juega una partida primero.</p>";
    }

    // Funcionalidad extra: Limpiar variables de Session Storage al cerrar sesión manualmente
    let btnCerrar = document.getElementById("btnCerrarSesion");
    if (btnCerrar) {
        btnCerrar.addEventListener("click", function(e) {
            e.preventDefault();
            sessionStorage.clear(); // Limpiamos nombres, config, y resultados base
            window.location.href = "logout.php";
        });
    }
});
