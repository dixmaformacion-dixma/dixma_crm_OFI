<?php

if($_SERVER['REQUEST_METHOD'] === 'POST' and isset($_POST['function'])){
    $commentFunctions = ["insertar_commentario", "editar_seguimentos_Insertar_commentario", "editar_commentario"];

    if(in_array($_POST['function'], $commentFunctions)){
        if(
            isset($_POST['commentario']) and
            isset($_POST['StudentCursoID'])
        ){
            if(trim($_POST['commentario']) == ""){
                return 0;
            }

            $idAlumno = isset($_POST['idAlumno']) ? $_POST['idAlumno'] : null;

            if($_POST['function'] === "editar_commentario"){
                if(!isset($_POST['idCommentario'])){
                    echo "<div class='alert alert-danger mb-0'> ERROR: Falta el identificador del comentario a editar </div>";
                    return;
                }

                $datosCommentario = [
                    'idCommentario' => $_POST['idCommentario'],
                    'commentario' => $_POST['commentario'],
                    'StudentCursoID' => $_POST['StudentCursoID']
                ];

                if(actualizarCursoCommentario($datosCommentario)){
                    echo "<div class='alert alert-success mb-0'> comentario actualizado exitosamente</div>";
                } else {
                    echo "<div class='alert alert-danger mb-0'> ERROR: el comentario no se pudo actualizar por alguna razón </div>";
                }
            } else {
                if(!isset($_POST['idAlumno'])){
                    echo "<div class='alert alert-danger mb-0'> ERROR: No se llenaron todos los campos necesarios </div>";
                    return;
                }

                $datosCommentario = [
                    'idAlumno' => $_POST['idAlumno'],
                    'commentario' => $_POST['commentario'],
                    'StudentCursoID' => $_POST['StudentCursoID'],
                    'author' => $_SESSION['usuario'],
                    'date' => date("Y-m-d"),
                    'created_at' => date("Y-m-d H:i:s")
                ];

                if(insertarCursoCommentario($datosCommentario)){
                    echo "<div class='alert alert-success mb-0'> comentario agregado exitosamente</div>";
                } else {
                    echo "<div class='alert alert-danger mb-0'> ERROR: el comentario no se pudo agregar por alguna razón </div>";
                }
            }

            $openCourseCollapseId = isset($_POST['openCourseCollapseId']) ? $_POST['openCourseCollapseId'] : '';
            $openCommentCollapseId = isset($_POST['openCommentCollapseId']) ? $_POST['openCommentCollapseId'] : '';
            $openCommentContainerId = isset($_POST['openCommentContainerId']) ? $_POST['openCommentContainerId'] : '';
            $openModalId = isset($_POST['openModalId']) ? $_POST['openModalId'] : '';

            if(!empty($idAlumno)){
                echo '
                    <script>
                    window.addEventListener("load", (event) => {
                        var alumnoElement = document.getElementById("Alumno'.$_POST['idAlumno'].'");
                        if (alumnoElement) {
                            alumnoElement.scrollIntoView();
                        }
                    });
                    </script>
                    ';
            }

            if($_POST['function'] === "editar_commentario"){
                echo '
                    <script>
                    window.addEventListener("load", function () {
                        var openModalId = ' . json_encode($openModalId) . ';
                        var openCourseCollapseId = ' . json_encode($openCourseCollapseId) . ';
                        var openCommentCollapseId = ' . json_encode($openCommentCollapseId) . ';
                        var openCommentContainerId = ' . json_encode($openCommentContainerId) . ';

                        function showCollapseById(elementId) {
                            if (!elementId) {
                                return;
                            }

                            var collapseElement = document.getElementById(elementId);
                            if (!collapseElement) {
                                return;
                            }

                            if (window.bootstrap && bootstrap.Collapse) {
                                bootstrap.Collapse.getOrCreateInstance(collapseElement, { toggle: false }).show();
                                return;
                            }

                            collapseElement.classList.add("show");
                        }

                        function revealCommentBlock() {
                            showCollapseById(openCourseCollapseId);

                            window.setTimeout(function () {
                                showCollapseById(openCommentCollapseId);

                                window.setTimeout(function () {
                                    var commentElement = document.getElementById(openCommentContainerId);
                                    if (commentElement) {
                                        commentElement.scrollIntoView({ behavior: "auto", block: "center" });
                                    }
                                }, 150);
                            }, 200);
                        }

                        if (openModalId) {
                            var modalElement = document.getElementById(openModalId);
                            if (modalElement && window.bootstrap && bootstrap.Modal) {
                                bootstrap.Modal.getOrCreateInstance(modalElement).show();
                                window.setTimeout(revealCommentBlock, 250);
                                return;
                            }
                        }

                        revealCommentBlock();
                    });
                    </script>
                    ';
            }
        }else{
            echo "<div class='alert alert-danger mb-0'> ERROR: No se llenaron todos los campos necesarios </div>";
        }
    }
}