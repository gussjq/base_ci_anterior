<!-- 

Vista para exportar documentos del listado tanto para word 

@author DEVELOPER 1 <correo@developer1> cel <1111111111>
@created 19-01-2015

-->


<html>
    <head></head>
    <body>

        <table style="width: 100%;">
            <tr>
                <td>
                    <table style="width: 100%;">
                        <tr>
                            <td style="text-align: center; font-weight: bold;" colspan="<?php echo count($config["aColumnas"]); ?>" ><?php echo ConfigHelper::get("cNombreEmpresa"); ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: center; font-size: 12px;" colspan="<?php echo count($config["aColumnas"]); ?>"><?php echo $tituloReporte; ?></td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td>
                    <table style="width: 100%; border-style: solid; border-width: 1px;">

                        <tr>
                            <?php foreach ($config["aColumnas"] as $value): ?>
                                <td style=" border-color: #333333; background-color: #e3e3e3; font-family: calibri; font-weight: bold;">
                                    <?php echo utf8_decode(lang($value->Etiqueta)); ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>

                        <?php if (count($data) > 0): ?>
                            <?php for ($i = 0; $i < count($data); $i++): ?>
                                <tr>
                                    <?php for ($j = 0; $j < count($data[$i]) - 1; $j++): ?>
                                        <td style="background-color: <?php echo ($i % 2) ? "#" . EXPORTAR_COLOR_FILA_UNO : "#" . EXPORTAR_COLOR_FILA_DOS ?> ; font-family: calibri; font-size: 12px;"><?php echo utf8_decode($data[$i][$j]); ?></td>
                                    <?php endfor; ?>
                                </tr>
                            <?php endfor; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="<?php echo (count($config["aColumnas"]) - 1) ?>"><?php echo lang("datatable_registrosnoencontrados"); ?></td>
                            </tr>
                        <?php endif; ?>
                            
                    </table>
                </td>
            </tr>
        </table>



    </body>
</html>
