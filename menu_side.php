<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="index.php" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <img src="assets/img/icon.png">
                        </span>
                        <span class="app-brand-text demo menu-text fw-bolder ms-2">CursoDev</span>
                    </a>

                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                        <i class="bx bx-chevron-left bx-sm align-middle"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    <!-- Dashboard -->
                    <li class="menu-item active open">
                        <a href="index.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div data-i18n="Analytics">Dashboard</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="instancia.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-message-alt-add"></i>
                            <div data-i18n="Analytics">Criar Instância</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="listar_instancias.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-list-ul"></i>
                            <div data-i18n="Analytics">Listar Instâncias</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class='menu-icon tf-icons bx bxl-whatsapp-square'></i>
                            <div data-i18n="Configurações">Disparador</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="enviar_msg_txt.php" class="menu-link">
                                    <div data-i18n="WhatsApp Api">Mensagem de Texto</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="enviar_msg_img.php" class="menu-link">
                                    <div data-i18n="WhatsApp Api">Mens. C/ Imagem</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="#" class="menu-link">
                                    <div data-i18n="WhatsApp Api">Mens. C/ Vídeo (PRO)</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class='menu-icon tf-icons bx bx-log-in-circle'></i>
                            <div data-i18n="Configurações">Integrações</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="#" class="menu-link">
                                    <div data-i18n="WhatsApp Api">Chatwoot (PRO)</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="#" class="menu-link">
                                    <div data-i18n="WhatsApp Api">Typebot (PRO)</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="#" class="menu-link">
                                    <div data-i18n="WhatsApp Api">Woocommerce (PRO)</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class='menu-icon tf-icons bx bx-cog'></i>
                            <div data-i18n="Configurações">Configurações</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="config_api.php" class="menu-link">
                                    <div data-i18n="WhatsApp Api">Evolution Api</div>
                                </a>
                            </li>

                            <li class="menu-item">
                                <a href="profile.php" class="menu-link">
                                    <div data-i18n="perfil">Perfil</div>
                                </a>
                            </li>

                            <li class="menu-item">
                                <a href="<?php echo $url_api."/docs" ?>" class="menu-link" target="_blank">
                                    <div data-i18n="Documentação EvolutionApi">Doc / Swagger</div>
                                </a>
                            </li>

                        </ul>
                    <li class="menu-item">
                        <a href="logout.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-window-close"></i>
                            <div data-i18n="Analytics">Desconectar</div>
                        </a>
                    </li>
                    <?php

                    $parametros = "/instance/fetchInstances";
                    $url = $url_api . $parametros;

                    // Dados do cabeçalho (headers)
                    $headers = array(
                        "Content-Type: application/json",
                        "apikey: " . $api_key
                    );

                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                    $response = curl_exec($ch);
                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);

                    if ($httpCode == 200) {
                        echo '<div style="padding:30%"><span class="badge rounded-pill bg-success">API ON-LINE!</span></div>';
                    } else {
                        echo '<div style="padding:30%"><span class="badge rounded-pill bg-danger">API OFF-LINE!</span></div>';
                    }

                    ?>
                    </li>
                    <li class="menu-item open">
                        <a href="#" class="menu-link">
                        <i class="menu-icon tf-icons bx bxs-registered"></i>
                            <div data-i18n="Analytics">Versão 1.2 (Free)</div>
                        </a>
                    </li>
                </ul>
            </aside>