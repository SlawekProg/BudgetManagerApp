<?php
    session_start();
    if(!isset($_SESSION['zalogowany'])){
        header('Location: index.html');
        exit();
    }
    require_once 'function.php';
    set_date();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base href="/BudgetManagerApp/">
    <link rel="preload" as="image" href="image/background.jpg">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bilans</title>
    <link rel="stylesheet" href="index.css">
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@coreui/coreui-pro@5.10.0/dist/js/coreui.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
</head>

<body style="margin: 0;">
    <header class="pb-4">
        <nav class="navbar navbar-expand-xl navbar-light fixed-top mask-custom shadow-0">
            <div class="container">
                <a class="navbar-brand apbud ">
                    <span style="color: #5e9693;">Aplikacja</span>
                    <span style="color: #fff;"> Budżetowa</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse pt-2" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto navHome">
                        <li class="nav-item ">
                            <a class="nav-link border-bottom" href="./home.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" class="bi bi-house-door mb-2 me-2" viewBox="0 0 16 16">
                                    <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4z"/>
                                </svg>Strona Główna
                            </a>  
                        </li>
                        <li class="nav-item">
                            <a class="nav-link border-bottom" href="./addExpenses.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" class="bi bi-cart-plus mb-2 me-2" viewBox="0 0 16 16">
                                    <path d="M9 5.5a.5.5 0 0 0-1 0V7H6.5a.5.5 0 0 0 0 1H8v1.5a.5.5 0 0 0 1 0V8h1.5a.5.5 0 0 0 0-1H9z"/>
                                    <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zm3.915 10L3.102 4h10.796l-1.313 7zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0m7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
                                </svg>Dodaj Wydatek
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link border-bottom" href="./addIncomes.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" class="bi bi-cash-stack mb-2 me-2" viewBox="0 0 16 16">
                                    <path d="M1 3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1zm7 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4"/>
                                    <path d="M0 5a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V7a2 2 0 0 1-2-2z"/>
                                </svg>Dodaj Przychód
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link border-bottom" href="./balanceView.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" class="bi bi-graph-up-arrow mb-2 me-2" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M0 0h1v15h15v1H0zm10 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V4.9l-3.613 4.417a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61L13.445 4H10.5a.5.5 0 0 1-.5-.5"/>
                                </svg>Bilans
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link border-bottom" href="./settings.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" class="bi bi-gear mb-2 me-2" viewBox="0 0 16 16">
                                    <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0"/>
                                    <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z"/>
                                </svg>Ustawienia
                            </a>
                        </li>
                    </ul>
                    <ul class="navbar-nav d-flex flex-row">
                        <li class="nav-item me-3 me-lg-0 navHome mt-3 pe-2">
                            <p>Użytkownik:</p>
                            <p><?php echo htmlspecialchars($_SESSION['username']); ?></p>
                        </li>
                        <li class="nav-item me-3 me-lg-0">
                            <a class="nav-link log" href="./index.html">Wyloguj</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>  
    </header>

    <main id="bilans" class="overflow-auto mt-5 mb-5" style="height: calc(100vh - 120px - 100px);">
        <section class="">
            <div class="container px-4 py-5" id="featured-3">
               <div class="row d-flex justify-content-center align-items-center">
                    <div class="col-md-12">
                        <h1 class="pb-2 border-bottom">Bilans Finansowy</h1> 
                        <p>Poniżej bilans miesięczny, ustawiając daty otrzymasz bilans z danego okresu.</p>
                    </div>
                    <form method="post">
                        <div class="d-flex justify-content-center mb-3 mt-0" >
                            <button type="submit" class="btn btn-light">Aktualizuj dane po zmanie daty</button>
                        </div>
                        <div class="col-ld-6 col-md-12 input-group">
                            <span class="input-group-text bg-white border-end-0 px-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="black" class="bi bi-calendar3" viewBox="0 0 16 16">
                                    <path d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2M1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857z"/>
                                    <path d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                                </svg>
                            </span>
                            <input type="date" id="date1" class="form-control form-control border-start-0 me-1" name="date_start" value="<?php remembering_entered_start_date() ?>">
                            <span class="input-group-text bg-white border-end-0 px-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="black" class="bi bi-calendar3" viewBox="0 0 16 16">
                                    <path d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2M1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857z"/>
                                    <path d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                                </svg>
                            </span>
                            <input type="date" id="date2" class="form-control form-control border-start-0" name="date_end" value="<?php remembering_entered_end_date() ?>">
                        </div>
                    </form>
                </div>
                <div class="row g-4 py-3 row-cols-1 row-cols-lg-3">
                  <div class="feature col">
                    <div class="text-bg-primary fs-2 rounded position-relative" style="height: auto;">
                        <div class="d-flex justify-content-center mb-2">
                            <button class="btn btn-light" onclick="toggleList()">Szczegóły Przychodów</button>
                        </div>
                        <div id="listaDanych" class="ukryta-lista position-absolute">
                            <?php load_user_incomes_fromDB_to_list(); ?>
                        </div>
                    </div>
                    <h2 class="fs-2 text-body-emphasis">Przychody</h2>
                    <p class="fw-bold">„Zarabianie to sztuka – praktykuj ją codziennie.” Sprawdź w szczegółach swoje dochody</p>
                    <div id="incomesPiechart"></div>
                  </div>
                  <div class="feature col">
                    <div class="text-bg-primary fs-2 rounded position-relative" style="height: auto;">
                        <div class="d-flex justify-content-center mb-2">
                          <button class="btn btn-light" onclick="toggleList2()">Szczegóły Wydatków</button>
                        </div>
                        <div id="listaDanych2" class="ukryta-lista position-absolute">
                            <?php load_user_expenses_fromDB_to_list(); ?>
                        </div>
                      </div>
                    <h2 class="fs-2 text-body-emphasis">Wydatki</h2>
                    <p class="fw-bold">„Jeśli nie kontrolujesz swoich wydatków, one kontrolują Ciebie.” Sprawdź w szczegółach wydatki</p>
                    <div id="expensesPiechart"></div>
                  </div>
                  <div class="feature col">
                    <div class="text-bg-primary fs-2 rounded position-relative" style="height: auto;">
                        <div class="d-flex justify-content-center mb-2">
                          <button class="btn btn-light" onclick="toggleList3()">Szczegóły Podsumowania</button>
                        </div>
                        <div id="listaDanych3" class="ukryta-lista position-absolute">
                            <?php load_user_financial_balance_fromDB_to_list(); ?>
                        </div>
                      </div>
                    <h2 class="fs-2 text-body-emphasis">Podsumowanie</h2>
                    <p class="fw-bold">„Bilans dodatni to nie tylko liczba - to spokój ducha.” Sprawdź w szczegółach swoje podsumowanie</p>
                    <div id="chart">
                        <canvas id="chartjs-line" height="100"></canvas>
                    </div>
                  </div>
                </div>
            </div>
        </section>         
    </main>

    <footer class="py-1 my-2 fixed-bottom">
        <div class="container">
          <ul class="nav justify-content-center border-bottom pb-1 mb-2">
            <li>
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="white" class="bi bi-envelope mt-2" viewBox="0 0 16 16">
                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/>
                </svg>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link px-2 text-light">slawomirbanas@pm.me</a>
            </li>
            <li>
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="white" class="bi bi-github mt-2 ms-5" viewBox="0 0 16 16">
                    <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27s1.36.09 2 .27c1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.01 8.01 0 0 0 16 8c0-4.42-3.58-8-8-8"/>
                </svg>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link px-2 text-light">GitHub</a>
            </li>
            <li>
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="white" class="bi bi-globe mt-2 ms-5" viewBox="0 0 16 16">
                    <path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m7.5-6.923c-.67.204-1.335.82-1.887 1.855A8 8 0 0 0 5.145 4H7.5zM4.09 4a9.3 9.3 0 0 1 .64-1.539 7 7 0 0 1 .597-.933A7.03 7.03 0 0 0 2.255 4zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a7 7 0 0 0-.656 2.5zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5zM8.5 5v2.5h2.99a12.5 12.5 0 0 0-.337-2.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5zM5.145 12q.208.58.468 1.068c.552 1.035 1.218 1.65 1.887 1.855V12zm.182 2.472a7 7 0 0 1-.597-.933A9.3 9.3 0 0 1 4.09 12H2.255a7 7 0 0 0 3.072 2.472M3.82 11a13.7 13.7 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5zm6.853 3.472A7 7 0 0 0 13.745 12H11.91a9.3 9.3 0 0 1-.64 1.539 7 7 0 0 1-.597.933M8.5 12v2.923c.67-.204 1.335-.82 1.887-1.855q.26-.487.468-1.068zm3.68-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.7 13.7 0 0 1-.312 2.5m2.802-3.5a7 7 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5zM11.27 2.461c.247.464.462.98.64 1.539h1.835a7 7 0 0 0-3.072-2.472c.218.284.418.598.597.933M10.855 4a8 8 0 0 0-.468-1.068C9.835 1.897 9.17 1.282 8.5 1.077V4z"/>
                </svg>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link px-2 text-light">Website</a
            ></li>
          </ul>
          <p class="text-center text-light">© Sławomir Banaś</p>
        </div>
      </footer>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
    crossorigin="anonymous"></script>
    <script src="index.js"></script>
</body>
</html>