<div class="row reservation-container">
    <div class="reservation-wrapper w-100">
        <div class="container">
            <div class="form-container">
                <form class="form">
                    <div class="steps step1 current">
                        <div class="step">
                            <div class="row no-gutters align-items-center">
                                <div class="col">
                                    <?php
                                        $placeholder = t("antours", "Placeholder select city", "Placeholder select city");
                                        $cities = generateSelectByMethod('getCities', "id_province", "name", array('placeholder' => $placeholder, 'data-message' => 'City is missing'));

                                        echo $cities;
                                    ?>
                                </div>
                                <div class="col-auto">
                                    <label class="col-form-label p-2">
                                        <span>
                                            Ida y vuelta
                                        </span>
                                        <input type="radio" data-id="round_trip" class="roundtrip" value="1" name="roundtrip" checked />
                                    </label>

                                    <label class="col-form-label p-2">
                                        <span>
                                            Solo ida
                                        </span>
                                        <input type="radio" data-id="round_trip" class="roundtrip" value="0" name="roundtrip" />
                                    </label>
                                </div>
                            </div>

                            <div class="row no-gutters align-items-center">
                                <div class="col">
                                    <label class="col-form-label p-2">
                                        Tu transporte comienza desde
                                    </label>
                                </div>

                                <div class="col-auto">
                                    <label class="col-form-label p-2">
                                        <span>
                                            Aeropuerto
                                        </span>
                                        <input type="radio" data-id="start_from" class="startfrom" value="0" name="startFrom" />
                                    </label>

                                    <label class="col-form-label p-2">
                                        <span>
                                            Domicilio
                                        </span>
                                        <input type="radio" data-id="start_from" class="startfrom" value="1" name="startFrom" checked />
                                    </label>
                                </div>
                            </div>

                            <div class="row no-gutters align-items-center">
                                <div class="col">
                                    <i class="fa fa-spinner fa-spin d-none spinner-commune"></i>
                                    <?php
                                        $placeholder = t("antours", "Placeholder select commune", "Placeholder select commune");
                                        $communes = generateSelectByMethod('getCommuneByCityId', "id_commune", "name", array('placeholder' => $placeholder, 'data-message' => 'Commune is missing'), 0);

                                        echo $communes;
                                    ?>
                                </div>

                                <div class="w-100">
                                </div>

                                <div class="col vertical-separator">
                                    <div class="form-row">
                                        <div class="col-7">
                                            <input type="text" data-id="street" placeholder="Calle" class="t-input-control form-control form-control-sm form-control-border" />
                                        </div>

                                        <div class="col">
                                            <input type="text" data-id="build_nro" placeholder="Número" class="t-input-control form-control form-control-sm form-control-border" />
                                        </div>

                                        <div class="col">
                                            <input type="text" data-id="dpto" placeholder="Departamento" class="t-input-control form-control form-control-sm form-control-border" />
                                        </div>
                                    </div>
                                </div>

                                <div class="w-100">
                                </div>

                                <div class="col vertical-separator">
                                    <input type="text" data-id="reference_point" placeholder="Referencia" class="t-input-control form-control form-control-sm form-control-border" />
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-primary btn-block btn-next-step" data-step=".step2">Next</button>
                    </div>

                    <div class="steps step2">
                        <div class="step">
                            <div class="row no-gutters align-items-center">
                                <div class="col-label">
                                    Ida
                                </div>

                                <div class="w-100 d-sm-none d-md-none d-lg-none d-xl-none"></div>

                                <div class="col">
                                    <div class="d-flex align-items-center container-field">
                                        <input type="text" id="go-date-transport" class="form-control reservation-field date-input" />
                                        <i class="fa fa-calendar field-icon" aria-hidden="true"></i>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="d-flex align-items-center container-field">
                                        <input type="text" id="go-time-transport" class="form-control reservation-field date-input" />
                                        <i class="fa fa-clock-o field-icon" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="row no-gutters align-items-center container-return-date">
                                <div class="col-label col-xs-12">
                                    Regreso
                                </div>

                                <div class="w-100 d-sm-none d-md-none d-lg-none d-xl-none"></div>

                                <div class="col">
                                    <div class="d-flex align-items-center container-field">
                                        <input type="text" id="return-date-transport" class="form-control reservation-field date-input" />
                                        <i class="fa fa-calendar field-icon" aria-hidden="true"></i>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="d-flex align-items-center container-field">
                                        <input type="text" id="return-time-transport" class="form-control reservation-field date-input" />
                                        <i class="fa fa-clock-o field-icon" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col btn-group">
                                <button class="btn btn-primary btn-next-step form-control" data-step=".step1">Prev</button>
                                <button class="btn btn-primary btn-next-step form-control" data-step=".step3">Next</button>
                            </div>
                        </div>
                    </div>

                    <div class="steps step3">
                        <div class="step">
                            <h1>I am step 3</h1>
                        </div>

                        <button class="btn btn-primary btn-next-step form-control" data-step=".step2">Make reservation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--<div class="row reservation-window">
    <div class="reservation-window-max-width content-open">
        <h4 style="margin: 0px; color: white; text-transform: uppercase;" class="text-uppercase">¿Necesita un translado? <span style="margin-left: 20px;">Reverse aquí</span></h4>
        <div class="reservation-content-form">
            <div class="form-wrapper">
                <div class="form-container">
                    <form class="form">
                        <div class="data-input-row">
                            <div class="label-container">
                                <span>
                                    Ida
                                </span>
                            </div>

                            <div class="data-container">
                                <div class="data-column">
                                    <div>
                                        <input id="go-date-transport" class="form-control date-input" />
                                    </div>

                                    <div>
                                        <span class="glyphicon glyphicon-calendar icon-color" aria-hidden="true"></span>
                                    </div>
                                </div>

                                <div class="data-column">
                                    <div>
                                        <input id="go-time-transport" placeholder="Hora de Ida" class="form-control date-input" />
                                    </div>

                                    <div>
                                        <span class="glyphicon glyphicon-time icon-color" aria-hidden="true"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="data-input-row">
                            <div class="label-container">
                                <span>
                                    Regreso
                                </span>
                            </div>

                            <div class="data-container">
                                <div class="data-column">
                                    <div>
                                        <input id="goback-date-transport" class="form-control date-input" />
                                    </div>

                                    <div>
                                        <span class="glyphicon glyphicon-calendar icon-color" aria-hidden="true"></span>
                                    </div>
                                </div>

                                <div class="data-column">
                                    <div>
                                        <input id="goback-time-transport" placeholder="Hora de regreso" class="form-control date-input" />
                                    </div>

                                    <div>
                                        <span class="glyphicon glyphicon-time icon-color" aria-hidden="true"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="data-output-row">
                            <table class="table table-output">
                                <thead>
                                    <tr>
                                        <th>Servicios</th>
                                        <th>Retiro</th>
                                        <th>Duración</th>
                                        <th>Llegada</th>
                                        <th>Tarifa</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td>
                                            Taxi Ejecutivo
                                        </td>

                                        <td>
                                            23:00
                                        </td>

                                        <td>
                                            1h 00m
                                        </td>

                                        <td>
                                            00:00
                                        </td>

                                        <td>
                                            $24.000
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            Transfer Exclusivo
                                        </td>

                                        <td>
                                            23:00
                                        </td>

                                        <td>
                                            1h 00m
                                        </td>

                                        <td>
                                            00:00
                                        </td>

                                        <td>
                                            $43.300
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
            <div class="button-container">
                <button class="btn btn-default submit-reservation text-uppercase" type="submit">Finalizar Reserva</button>
            </div>
        </div>
    </div>
</div>-->