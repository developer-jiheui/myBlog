@extends('layouts.main')

@section('content')
    <article class="experience active" data-page="experience">
        <header>
            <h2 class="h2 article-title">Experience</h2>
        </header>

        <section class="timeline">
            <div class="title-wrapper">
                <div class="icon-box">
                    <ion-icon name="book-outline" role="img" class="md hydrated" aria-label="book outline"></ion-icon>
                </div>

                <h3 class="h3">Work</h3>
            </div>

            <ol class="timeline-list">
                <li class="timeline-item">
                    <div class="timeline-work-container">
                            <a href="" target="_blank">
                                <h4 class="h4 timeline-item-title">Einsoft</h4></a>
                            <p class="timeline-item-position">Fullstack Web Developer</p>
                    </div>

                    <span>Dec 2023 — Dec 2024</span>

                    <p class="timeline-text">
                        Build a website in startup env
                    </p>
                </li>

                <li class="timeline-item">
                    <div class="timeline-work-container">
                        <a href="https://www.outrider.ai/" target="_blank">
                            <img src="./assets/images/company/logo-outrider.png" alt="mobile app icon" width="24" style="margin-right: 6px; border-radius: 4px"></a>
                        <div class="timeline-work-text">
                            <a href="https://www.outrider.ai/" target="_blank">
                                <h4 class="h4 timeline-item-title">Outrider</h4></a>
                            <p class="timeline-item-position">
                                Software Engineer, Cloud Applications Intern
                            </p>
                        </div>
                    </div>

                    <span>June 2023 — September 2023</span>

                    <p class="timeline-text">
                        Full-stack internal tool for autonomous vehicle test
                        operations | Next.js, TypeScript, Node.js, Jest
                    </p>
                </li>

                <li class="timeline-item">
                    <div class="timeline-work-container">
                        <a href="https://www.brex.com/" target="_blank">
                            <img src="./assets/images/company/logo-brex.png" alt="mobile app icon" width="24" style="margin-right: 6px; border-radius: 4px"></a>
                        <div class="timeline-work-text">
                            <a href="https://www.brex.com/" target="_blank">
                                <h4 class="h4 timeline-item-title">Brex</h4></a>
                            <p class="timeline-item-position">
                                Software Engineer Intern
                            </p>
                        </div>
                    </div>

                    <span>May 2022 — Aug 2022</span>

                    <p class="timeline-text">
                        Mobile Policy Validation | React Native, TypeScript,
                        GraphQL<br>
                        Globalized state property of addresses in internal tool | SQL,
                        Retool
                    </p>
                </li>

                <li class="timeline-item">
                    <div class="timeline-work-container">
                        <a href="https://www.ringleplus.com/en/student/landing/home" target="_blank">
                            <img src="./assets/images/company/logo-ringle.png" alt="mobile app icon" width="24" style="margin-right: 6px; border-radius: 4px"></a>
                        <div class="timeline-work-text">
                            <a href="https://www.ringleplus.com/en/student/landing/home" target="_blank">
                                <h4 class="h4 timeline-item-title">Ringle</h4></a>
                            <p class="timeline-item-position">
                                Technical Product Manager Intern
                            </p>
                        </div>
                    </div>

                    <span>Apr 2020 - Mar 2021</span>

                    <p class="timeline-text">
                        Slackbot metric tracking automation | Ruby, SQLite
                        <br>Improved user retention with analytics page and UX
                        enhancements | JavaScript, SQL
                    </p>
                </li>
            </ol>
        </section>

        <section class="timeline">
            <div class="title-wrapper">
                <div class="icon-box">
                    <ion-icon name="book-outline" role="img" class="md hydrated" aria-label="book outline"></ion-icon>
                </div>

                <h3 class="h3">Education</h3>
            </div>

            <ol class="timeline-list">
                <li class="timeline-item">
                    <a href="https://www.mtholyoke.edu/" target="_blank">
                        <h4 class="h4 timeline-item-title-edu">
                            Mount Holyoke College
                        </h4></a>
                    <span>2021 — 2023</span>

                    <p class="timeline-text">
                        Bachelor of Arts in Computer Science, GPA: 3.86<br>
                        · Chair of the Website team for
                        <i>Computer Science Society(CS Student Organization)</i><br>
                        · Teaching Assistant for <i>Intro to Computing Systems</i> and
                        <i>Discrete Mathematics</i><br>
                        · Peer Mentor for <i>Intro to Computer Science</i>
                    </p>
                </li>

                <li class="timeline-item">
                    <a href="https://www.ait-budapest.com/" target="_blank">
                        <h4 class="h4 timeline-item-title-edu">AIT-Budapest</h4></a>
                    <span>2022</span>

                    <p class="timeline-text">
                        Computer Science Study Abroad Program in Budapest, Hungary
                    </p>
                </li>

                <li class="timeline-item">
                    <a href="https://www.dongguk.edu/main" target="_blank">
                        <h4 class="h4 timeline-item-title-edu">
                            Dongguk University
                        </h4></a>
                    <span>2018 — 2019</span>

                    <p class="timeline-text">
                        Physics and Semiconductor → Computer Engineering → Transfer to
                        Mount Holyoke College
                    </p>
                </li>
            </ol>
        </section>
    </article>
@endsection
