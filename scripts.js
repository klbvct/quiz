// Функция проверки валидации и отправки данных пользователя в localStorega
(function () {
  'use strict'

  // Получите все формы, к которым мы хотим применить пользовательские стили проверки Bootstrap
  var forms = document.querySelectorAll('.needs-validation')

  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        } else {
          event.preventDefault(); // Предотвращаем стандартное поведение формы (перезагрузка страницы)
          
          // Сохраняем данные в LocalStorage
          var email = document.getElementById('exampleFormControlInputEmail').value;
          var name = document.getElementById('exampleFormControlInputName').value;
          var surname = document.getElementById('exampleFormControlInputFirstName').value;
          var birthday = document.getElementById('exampleFormControlInputBirthday').value;

          localStorage.setItem('userEmail', email);
          localStorage.setItem('userName', name);
          localStorage.setItem('userSurname', surname);
          localStorage.setItem('userBirthday', birthday);

          // Переход на страницу с тестом
          window.location.href = "module1.html";
        }

        form.classList.add('was-validated');
      }, false);
    });
})();

// Функция записи ответов в localStorage для всех Модулей
const allAnswers = JSON.parse(localStorage.getItem('allAnswers')) || []; // Загружаем данные из localStorage
(function () {
    'use strict';
  
    document.getElementById('moduleForm').addEventListener('submit', function(event) {
        event.preventDefault(); // предотвращаем стандартную отправку формы
        
        const answers = {};
        const questions = document.querySelectorAll('.question'); // выбираем все элементы с классом question
        
        questions.forEach((question) => {
            const questionTitle = question.querySelector('.question_name h6').textContent.trim(); // получаем текст заголовка вопроса
            
            const answerItems = question.querySelectorAll('.answer-item');

            if (answerItems.length > 0) {
                answerItems.forEach((answerItem) => {
                    const subQuestionText = answerItem.querySelector('span').textContent.trim(); // получаем текст подвопроса
                    const selectedRadio = answerItem.querySelector('input[type="radio"]:checked');
                    const selectedSelect = answerItem.querySelector('select.form-select');
                    
                    if (selectedRadio) {
                        answers[`${questionTitle} - ${subQuestionText}`] = selectedRadio.nextElementSibling.textContent.trim();
                    } else if (selectedSelect && selectedSelect.value !== "Оберіть відповідь") {
                        answers[`${questionTitle}${subQuestionText}`] = selectedSelect.value;
                    } else {
                        answers[`${questionTitle}${subQuestionText}`] = 'Не відповіли';
                    }
                });
            } else {
                const selectedRadio = question.querySelector('input[type="radio"]:checked');
                const selectedSelect = question.querySelector('select.form-select');
                
                if (selectedRadio) {
                    answers[questionTitle] = selectedRadio.nextElementSibling.textContent.trim();
                } else if (selectedSelect && selectedSelect.value !== "Оберіть відповідь") {
                    answers[questionTitle] = selectedSelect.value;
                } else {
                    answers[questionTitle] = 'Не відповіли';
                }
            }
        });
        
        const moduleName = document.querySelector('.modul h1.blue').textContent.trim();
        const name = localStorage.getItem('userName');
        const surname = localStorage.getItem('userSurname');
        const email = localStorage.getItem('userEmail');
        const birthday = localStorage.getItem('userBirthday');

        const moduleData = {
            moduleName: moduleName,
            answers: answers
        };

        // Проверка на наличие записи с таким же модулем
        const existingModuleIndex = allAnswers.findIndex(item => item.moduleName === moduleName);

        if (existingModuleIndex !== -1) {
            // Если запись с таким модулем уже существует, перезаписываем её
            allAnswers[existingModuleIndex] = moduleData;
        } else {
            // Если записи нет, добавляем новую
            allAnswers.push(moduleData);
        }

        localStorage.setItem('allAnswers', JSON.stringify(allAnswers)); // сохраняем массив в localStorage

        // Переход на следующую страницу с тестом
        const currentModuleIndex = parseInt(window.location.href.split('module')[1]) || 1;
        if (currentModuleIndex < 8) {
            const nextModuleIndex = currentModuleIndex + 1;
            window.location.href = `module${nextModuleIndex}.html`;
        } else {
            window.location.href = "send_answers.html";
        }
    });
})();


// Проверка, на какой странице мы находимся
const currentPage = window.location.pathname.split('/').pop(); // Получает имя текущей страницы

// Функція створення запитань для Модуля 1
(function () {
  'use strict'

  if (currentPage === 'module1.html') {

    let questions = [
      {
        question: "1.",
        answers: [
            "а) Доглядати тварин",
            "б) Експлуатувати прилади, слідкувати за ними, регулювати їх"
        ]
      },
      {
          question: "2.",
          answers: [
              "а) Допомагати хворим людям",
              "б) Складати таблиці, креслити схеми, розробляти комп’ютерні програми"
          ]
      },
      {
          question: "3.",
          answers: [
              "а) Розглядати книжкові ілюстрації",
              "б) Стежити за станом та розвитком рослин"
          ]
      },
      {
          question: "4.",
          answers: [
              "а) Обробляти матеріали (дерево, тканину, метал, пластмасу, тощо)",
              "б) Доводити товари до споживачів, рекламувати, продавати"
          ]
      },
      {
          question: "5.",
          answers: [
              "а) Обговорювати наукові книги, статті",
              "б) Обговорювати художні книжки (чи п'єси, концерти)"
          ]
      },
      {
          question: "6.",
          answers: [
              "а) Вирощувати тварин будь-якої породи",
              "б) Тренувати товаришів (або молодших) у виконанні будь-яких дій (трудових, навчальних, спортивних)"
          ]
      },
      {
          question: "7.",
          answers: [
              "а) Копіювати малюнки, зображення чи настроювати музичні інструменти",
              "б) Керувати будь-яким вантажним (підйомним чи транспортним) засобом (підйомним краном, трактором, телевізором та ін.)"
          ]
      },
      {
          question: "8.",
          answers: [
              "а) Повідомляти, роз'яснювати людям потрібні їм відомості (у довідковому бюро, на екскурсії тощо)",
              "б) Оформляти вистави, вітрини (чи брати участь у підготовці п'єс, концертів)"
          ]
      },
      {
          question: "9.",
          answers: [
              "а) Ремонтувати речі, вироби (одяг, техніку, житло)",
              "б) Шукати та виправляти помилки у текстах, таблицях, малюнках"
          ]
      },
      {
          question: "10.",
          answers: [
              "а) Лікувати тварин",
              "б) Виконувати обчислення та розрахунки"
          ]
      },
      {
          question: "11.",
          answers: [
              "а) Спостерігати за рослинами та вирощувати їх",
              "б) Конструювати, проектувати нові види різних виробів (Дизайн будинків, машини, одягу, продуктів харчування тощо)"
          ]
      },
      {
          question: "12.",
          answers: [
              "а) Розбирати суперечки, сварки між людьми: переконувати, роз'яснювати, карати, заохочувати",
              "б) Розбиратися в кресленнях, схемах, таблицях (перевіряти, уточнювати, упорядковувати)"
          ]
      },
      {
          question: "13.",
          answers: [
              "а) Спостерігати, вивчати роботу позакласних занять, гуртків, секцій",
              "б) Спостерігати, вивчати життя мікробів"
          ]
      },
      {
          question: "14.",
          answers: [
              "а) Обслуговувати, налагоджувати медичні прилади, апарати",
              "б) Надавати людям медичну допомогу при пораненнях, забоях, опіках тощо."
          ]
      },
      {
          question: "15.",
          answers: [
              "а) Складати точні описи - звіти про спостереження, явища, події, вимірюваних об'єктах та ін.",
              "б) Художньо описувати, зображати події (спостерігати та представляти)"
          ]
      },
      {
          question: "16.",
          answers: [
              "а) Робити лабораторні аналізи у лікарні",
              "б) Приймати, оглядати хворих, розмовляти з ними, призначати лікування"
          ]
      },
      {
          question: "17.",
          answers: [
              "а) Фарбувати або розписувати стіни приміщень, поверхню виробів",
              "б) Здійснювати монтаж машин, приладів"
          ]
      },
      {
          question: "18.",
          answers: [
              "а) Організовувати походи однолітків чи молодших дітей до театрів, музеїв, екскурсій, туристичних походів тощо.",
              "б) Грати на сцені, брати участь у концертах"
          ]
      },
      {
          question: "19.",
          answers: [
              "а) Виготовляти за кресленнями деталі, вироби (машини, одяг), будувати будівлі",
              "б) Займатися кресленням, копіювати креслення, карти"
          ]
      },
      {
          question: "20.",
          answers: [
              "а) Вести боротьбу із хворобами рослин, із шкідниками лісу, саду",
              "б) Працювати з машинами (комп'ютер, роботи, смартфони, планшети та ін.)"
          ]
      }
    ];

    let questionsContainer = document.getElementById('questionsContainer');

    questions.forEach((item, index) => {
        let questionDiv = document.createElement('div');
        questionDiv.classList.add('col-md-6', 'offset-md-3', 'mb-4');

        questionDiv.innerHTML = `
            <div class="question">
                <div class="question_name">
                    <h6>${item.question}</h6>
                </div>
                <div class="answers_list">
                    ${item.answers.map((answer, i) => `
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="question_${index + 1}" id="q${index + 1}a${i + 1}">
                            <label class="form-check-label" for="q${index + 1}a${i + 1}">
                                ${answer}
                            </label>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;

        questionsContainer.appendChild(questionDiv);
    });

  }

})();

// Функция создания вопросов для Модуля 2
(function () {
  'use strict'

  if (currentPage === 'module2.html') {

    let questions = [
          "Дивитися передачі та читати про рослини, життя Землі, еволюцію тварин, появу та закономірності життя на планеті",
          "Вивчати географію, столиці, з'ясовувати де знаходиться та чи інша країна чи місто",
          "Дивитися відео, читати про дослідження землі, континентів, океанів та ін.",
          "Цікава анатомія та фізіологія людини",
          "Ви любите наводити порядок і розслабляєтесь прибираючи",
          "Вам цікаві відкриття у фізиці, життя та діяльність відомих вчених",
          "Читати про сучасні відкриття в хімії або про дослідження в цій галузі",
          "Вас цікавить технічна сторона пристроїв та їх функціонування",
          "Робототехніка, електроніка, машини, планшети, пристрої, техніка, смартфони",
          "Знайомитись з ювелірними властивостями металів, можливістю створення виробів",
          "Будівництво будинків - захоплюючий процес, створювати та формувати простір де ми живемо",
          "Цікаві різні види транспорту (авто, літаки, яхти або транспорт майбутнього.)",
          "Мені подобається літати на літаках, мені цікаво як вони влаштовані",
          "Цікава військова техніка",
          "Історичні події - захоплюючі",
          "Мені подобається читати художню літературу",
          "Журналістика цікава. Хотілося б писати статті",
          "Обговорювати поточні справи та події у навчальному закладі, місті, країні",
          "Цікавитись роботою вчителя та викладача",
          "Робота поліцейських викликає в мені інтерес",
          "Дбати про порядок у власних речах, гарному вигляді приміщення",
          "Математика зрозуміла, хоча потребує практики",
          "Вивчення економік країн",
          "Займатися іноземною мовою",
          "Історія мистецтва – дуже цікава",
          "Дізнаватись про життя знаменитостей, зустрічатися з ними, розглядати їх фотографії",
          "Я добре співаю, люблю музику, знаюся на ній",
          "Цікавлюсь та розбираюся у спорті",
          "Вивчати біологію, ботаніку, зоологію",
          "Знайомитися з різними країнами з відео чи власним досвідом",
          "Як з'являється нафта і різні копалини, вугілля чи метал – цікаво",
          "Досягненнями медицини - вивчати та цікавитися",
          "Потрапити до майстерні дизайнера одягу",
          "Фізика – це майбутнє та найголовніша наука",
          "Вивчати хімічні явища у природі",
          "Знайомитись з новітніми досягненнями технологій",
          "Цікавитись роботою електромашин та електрики",
          "Знайомитись з різними вимірювальними інструментами, що використовуються у процесі металообробки",
          "Спостерігати за виготовленням виробів із дерева (наприклад, меблів)",
          "Спостерігати за роботою будівельників",
          "Цікавитись транспортом",
          "Ходити на кораблях, бути моряком, працювати на кораблі",
          "Відео на військові теми, знайомитися з історією великих битв",
          "Обговорювати поточні політичні події в країні та за кордоном",
          "Читати статті відомих журналістів",
          "Слухати новини, дивитися відео про новини",
          "Дізнаватись про події, що відбуваються в місті, країні",
          "Пояснювати друзям важкі для розуміння питання",
          "Справедливо розсудити вчинок знайомого чи літературного героя",
          "Купувати продукти, організовувати харчування членів сім'ї",
          "Відкриття в математиці, програмуванні та штучному інтелекті",
          "Цікавитись економічними передачами",
          "Читати художню літературу іноземною мовою",
          "Займатися художнім оформленням свят",
          "Відвідувати театр",
          "Слухати класичну чи сучасну музику",
          "Відвідувати спортивні змагання, дивитися спортивні передачі",
          "Відвідувати додаткові заняття з біології",
          "Відвідувати додаткові заняття з географії",
          "Колекціонувати мінерали, робити вироби з них",
          "Вивчати функції організму людини, причини виникнення та способи лікування хвороб людини",
          "Готувати обід вдома",
          "Здійснювати демонстрацію фізичних дослідів",
          "Участь в організації дослідів з хімічними речовинами",
          "Ремонтувати різні механізми",
          "Здійснювати заміри в електромережах за допомогою приладів",
          "Конструювати різні предмети та деталі з металу",
          "Художньо обробляти дерево (вирізати, випилювати)",
          "Робити ескізи або виконувати креслення будівельних об'єктів",
          "Виявляти інтерес до автомобільного транспорту",
          "Захоплюватися парашутним спортом, авіамоделюванням",
          "Займатися спортивною стрільбою",
          "Вивчати історію виникнення різних народів та держав",
          "Писати вірші",
          "Спостерігати за поведінкою та життям інших людей",
          "Виконувати організаційну громадську роботу",
          "Проводити час з маленькими дітьми, займатися та грати з ними",
          "Встановлювати дисципліну серед однолітків",
          "Спостерігати за роботою працівників сфери обслуговування (кухар, офіціант, продавець)",
          "Брати участь у математичних олімпіадах",
          "Переглядати економічні огляди в мережі",
          "Дивитися відео іноземною мовою",
          "Відвідувати музеї, художні виставки, музеї сучасного мистецтва",
          "Публічно виступати",
          "Грати на музичному інструменті",
          "Брати участь у спортивних змаганнях",
          "Спостерігати за зростанням та розвитком тварин, рослин",
          "Збирати інформацію з географії",
          "Відвідувати краєзнавчий музей",
          "Занурюватись у роботу лікаря, фармацевта",
          "Шити для себе",
          "Цікавитись фізикою",
          "Цікавитись хімією",
          "Працювати з технікою",
          "Знайомитись з пристроями електроапаратури",
          "Займатися слюсарною справою",
          "Виконувати столярні роботи",
          "Приймати участь у ремонтних роботах або декоруванні приміщень",
          "Допомагати поліції у роботі з пішоходами за правилами вуличного руху",
          "Бути в гребних або яхт-клубах",
          "Брати участь у воєнізованих іграх",
          "Знайомитись з історичними місцями",
          "Бути членом літературного гуртка",
          "Спостерігати за веденням журналістами телепередач",
          "Публічно виступати з повідомленнями та доповідями",
          "Організовувати роботу дітей",
          "Встановлювати приховані причини вчинків та поведінки людей",
          "Надавати різні послуги іншим людям",
          "Вирішувати складні математичні завдання",
          "Точно вести розрахунок своїх коштів",
          "Відвідувати курси з вивчення іноземної мови",
          "Займатися в художній секції",
          "Виступати на сцені, займатись творчістю як аматор",
          "Відвідувати музичну школу",
          "Займатися у спортивній секції",
          "Брати участь у біологічних олімпіадах та конкурсах",
          "Подорожувати та описувати свої подорожі",
          "Брати участь у геологічній експедиції",
          "Доглядати хворих",
          "Відвідувати кулінарні конкурси та виставки",
          "Участь у конкурсних заходах з фізики",
          "Вирішувати складні завдання з хімії",
          "Розбиратися в технічних схемах та кресленнях",
          "Розбиратися у складних схемах",
          "Знайомитися з новою технікою, розумітися на роботі обладнання",
          "Майструвати щось із дерева своїми руками",
          "Допомагати у будівельних роботах",
          "Брати участь в обслуговуванні та ремонті автомобіля",
          "Мріяти про самостійні польоти літаком",
          "Суворо виконувати розпорядок дня",
          "Публічно виступати з доповідями на історичні теми",
          "Працювати з літературними джерелами",
          "Брати участь у дискусіях, конференціях",
          "Організовувати та проводити колективні заходи",
          "Обговорювати питання виховання дітей та підлітків",
          "Допомагати працівникам поліції",
          "Постійно спілкуватися з різними людьми",
          "Брати участь у математичних олімпіадах, конференціях",
          "Цікавитись питаннями ціноутворення, нарахування заробітної плати",
          "Спілкуватися іноземною мовою",
          "Брати участь у художніх виставках",
          "Брати участь у театральних виставах",
          "Брати участь у музичних конкурсах",
          "Регулярно тренуватися та брати участь у спортивних змаганнях",
          "Вирощувати рослини, доглядати домашніх тварин",
          "Проводити фотозйомку місцевості та природи",
          "Здійснювати тривалі, важкі походи",
          "Цікавитись діяльністю медичних працівників",
          "Шити, кроїти, створювати моделі одягу",
          "Вирішувати складні завдання з фізики",
          "Відвідувати з екскурсіями хімічні підприємства",
          "Брати участь у виставках технічної творчості",
          "Розбирати та збирати планшети, смартфони",
          "Працювати з конструкторами",
          "Виконувати за кресленнями роботи з дерева",
          "Брати участь в будівництві",
          "Вивчати правила дорожнього руху для водіїв",
          "Вивчати морську справу",
          "Цікавитись історією головнокомандувачів та полководців",
          "Знайомство з історичними місцями.",
          "Писати оповідання",
          "Писати статті у блог",
          "Виконувати громадські доручення",
          "Організовувати дитячі ігрові та святкові заходи",
          "Вивчати законодавчі документи, громадянське право",
          "Задовольняти потреби людей у тих чи інших послугах",
          "Виконувати математичні розрахунки за формулами",
          "Цікавитись проблемами різних типів економіки",
          "Участь в олімпіадах з іноземної мови",
          "Відвідувати художні музеї",
          "Грати на сцені у спектаклях, знімати відео своєї гри",
          "Слухати класичну музику, розумітися на ній",
          "Допомагати викладачу фізкультури у проведенні занятть"
    ];

    function createQuestionElement(questionText, questionNumber) {
      let questionWrapper = document.createElement('div');
      questionWrapper.className = 'col-md-6 offset-md-3';

      let questionDiv = document.createElement('div');
      questionDiv.className = 'question';

      let questionNameDiv = document.createElement('div');
      questionNameDiv.className = 'question_name';
      
      let questionTitle = document.createElement('h6');
      questionTitle.textContent = `${questionNumber}. ${questionText}`;

      questionNameDiv.appendChild(questionTitle);

      let answersListDiv = document.createElement('div');
      answersListDiv.className = 'answers_list';

      let selectElement = document.createElement('select');
      selectElement.className = 'form-select form-select-sm';
      selectElement.setAttribute('aria-label', '.form-select-sm');

      let options = [
        { value: '', text: 'Оберіть відповідь' },
        { value: '++', text: '++' },
        { value: '+', text: '+' },
        { value: '0', text: '0' },
        { value: '-', text: '-' },
        { value: '--', text: '--' }
      ];

      options.forEach(option => {
        let optionElement = document.createElement('option');
        optionElement.value = option.value;
        optionElement.textContent = option.text;
        selectElement.appendChild(optionElement);
      });

      answersListDiv.appendChild(selectElement);
      questionDiv.appendChild(questionNameDiv);
      questionDiv.appendChild(answersListDiv);
      questionWrapper.appendChild(questionDiv);

      return questionWrapper;
    }

    function renderQuestions() {
      let questionsContainer = document.getElementById('questionsContainer');
      questions.forEach((question, index) => {
        let questionElement = createQuestionElement(question, index + 1);
        questionsContainer.appendChild(questionElement);
      });
    }

    // Вызов функции для генерации вопросов
    renderQuestions();

  }
})();

// Функція створення запитань для Модуля 3
(function () {
  'use strict'

  if (currentPage === 'module3.html') {

    let questions = [
      {
        question: "1. Мені хотілося б в моїй професійній діяльності:",
        answers: [
            "а) спілкуватися з різними людьми",
            "б) що-небудь робити своїми руками - меблі, одяг, машини і т.д.",
            "в) знімати фільми, малювати, писати книги, виступати на сцені і т.д."
        ]
      },
      {
          question: "2. У книзі або кіно мене найбільше притягає:",
          answers: [
              "а) художня форма, майстерність письменника або режисера",
              "б) сюжет, дія героїв",
              "в) інформація, яка може стати в нагоді в житті"
          ]
      },
      {
          question: "3. Мене потішить премія або нагорода:",
          answers: [
              "а) в області науки",
              "б) за суспільну діяльність",
              "в) в області мистецтва"
          ]
      },
      {
          question: "4. Я скоріш погоджусь стати:",
          answers: [
              "а) керівником банку",
              "б) головним інженером на виробництві",
              "в) організатором експедиції"
          ]
      },
      {
          question: "5. Майбутнє людей визначає:",
          answers: [
              "а) досягнення науки",
              "б) розвиток виробництва",
              "в) взаєморозуміння серед людей"
          ]
      },
      {
          question: "6. На місці директора школи я в першу чергу зроблю:",
          answers: [
              "а) благоустрій школи (їдальня, спортзал, компʼютери)",
              "б) відтворення дружнього колективу",
              "в) розробку нових технологій навчання"
          ]
      },
      {
          question: "7. На технічній виставці мене більш за все привабить:",
          answers: [
              "а) зовнішній вигляд експонатів (колір, форма)",
              "б) внутрішня будова експонатів (механізм)",
              "в) практичне застосування експонатів"
          ]
      },
      {
          question: "8. В людях я ціную перед усе:",
          answers: [
              "а) мужність, сміливість, витривалість",
              "б) дружність, чуткість, чуйність",
              "в) відповідальність, акуратність"
          ]
      },
      {
          question: "9. В вільний час від роботи я буду:",
          answers: [
              "а) писати щось в блог, слухати музику або малювати",
              "б) проводити різні дослідження",
              "в) тренуватися"
          ]
      },
      {
          question: "10. В закордонних поїздках мене більше за все привабить:",
          answers: [
              "а) альпінізм, віндсерфінг, байдарки, яхтинг",
              "б) ділове спілкування",
              "в) можливість знайомства з історією й культурою іншої країни"
          ]
      },
      {
        question: "11. Мені цікавіше вести бесіду:",
        answers: [
            "а) про машині нового типу",
            "б) про нові відкриття в науці і технологіях",
            "в) про людські взаємовідношення"
        ]
      },
      {
          question: "12. Якщо б в моїй школі було всього три гуртки, я б вибрав:",
          answers: [
              "а) економічний",
              "б) інженерний",
              "в) туристичний"
          ]
      },
      {
          question: "13. В школі більше уваги слід приділяти:",
          answers: [
              "а) покращенню взаємовідношень",
              "б) підтримці здоровʼя учнів, заняттям спортом",
              "в) укріпленню дисципліни"
          ]
      },
      {
          question: "14. Я з більшою цікавістю дивлюсь:",
          answers: [
              "а) науково-популярні фільми",
              "б) програми про культуру і спорт",
              "в) спортивні програми"
          ]
      },
      {
          question: "15. Мені було б цікаво працювати:",
          answers: [
              "а) з машинами і механізмами",
              "б) з обʼєктами природи",
              "в) з дітьми або однолітками"
          ]
      },
      {
          question: "16. Школа в першу чергу повинна:",
          answers: [
              "а) давати знання і вміння",
              "б) навчати спілкування з іншими людьми",
              "в) навчати навичкам роботи"
          ]
      },
      {
          question: "17. Кожна людина повинна:",
          answers: [
              "а) вести здоровий спосіб життя",
              "б) мати можливість займатися творчістю",
              "в) мати комфортні побутові умови"
          ]
      },
      {
          question: "18. Для благополуччя суспільства в першу чергу необхідно:",
          answers: [
              "а) захист інтересів і прав громадян",
              "б) піклування про матеріальне благополуччя людей",
              "в) наука і технічний прогрес"
          ]
      },
      {
          question: "19. Мені більш за все подобаються уроки:",
          answers: [
              "а) фізкультури",
              "б) математики",
              "в) праці і технологій"
          ]
      },
      {
          question: "20. Мені цікавіше було б:",
          answers: [
              "а) планувати виробництво продукції",
              "б) робити вироби",
              "в) займатися продажем продукції"
          ]
      },
      {
          question: "21. Я читаю блоги, знаходжу інформацію:",
          answers: [
              "а) про видатних вчених та їх відкриття, технології",
              "б) про творчість художників і музикантів",
              "в) про цікаві винаходи"
          ]
      },
      {
          question: "22. Вільний час я скоріш проводжу:",
          answers: [
              "а) роблячи щось в домі",
              "б) з книгою або дивлячись цікаві відео",
              "в) на виставах, концертах і інше."
          ]
      },
      {
          question: "23. Велику цікавість в мене викликає повідомлення:",
          answers: [
              "а) про творчий захід",
              "б) про ситуацію на фондовій біржі або фінансові новини",
              "в) про наукове відкриття і новини в світі технологій"
          ]
      },
      {
          question: "24. Я віддаю перевагу працювати або навчатися:",
          answers: [
              "а) в приміщенні, де багато людей",
              "б) в незвичайних умовах",
              "в) в звичайному кабінеті, щоб ніхто не заважав"
          ]
      }
    ];
  

    let questionsContainer = document.getElementById('questionsContainer');

    questions.forEach((item, index) => {
        let questionDiv = document.createElement('div');
        questionDiv.classList.add('col-md-6', 'offset-md-3', 'mb-4');

        questionDiv.innerHTML = `
            <div class="question">
                <div class="question_name">
                    <h6>${item.question}</h6>
                </div>
                <div class="answers_list">
                    ${item.answers.map((answer, i) => `
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="question_${index + 1}" id="q${index + 1}a${i + 1}">
                            <label class="form-check-label" for="q${index + 1}a${i + 1}">
                                ${answer}
                            </label>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;

        questionsContainer.appendChild(questionDiv);
    });

  }

})();

// Функція створення запитань для Модуля 4
(function () {
    'use strict'
  
    if (currentPage === 'module4.html') {

        const questions = [
            "Акуратність (чистоплотність), вміння тримати в порядку речі, порядок в справах",
            "Вихованість (гарні манери)",
            "Високі запроси (високі вимоги до життя)",
            "Життєрадісність (почуття гумору)",
            "Виконання завдань (дисциплінованість)",
            "Незалежність (вміння діяти самостійно)",
            "Непримиренність до недоліків в собі та інших",
            "Вченість (широта знань, висока загальна культура)",
            "Відповідальність (почуття обов'язку, уміння тримати слово)",
            "Раціоналізм (логічно мислити, приймати обдумані рішення)",
            "Самоконтроль (стриманість, самодисципліна)",
            "Сміливість в отстоюванні своєї думки, поглядів",
            "Тверда воля (уміння наполягти на своєму, не відступати перед труднощами)",
            "Терпимість (до поглядів і думки інших, уміння прощати іншим їх помилки)",
            "Широта поглядів (уміння зрозуміти чужу точку зору, поважати інші смаки, звичаї, звички)",
            "Чесність (правдивість, щирість)",
            "Ефективність в справах (працьовитість, продуктивність в роботі)",
            "Чуйність (дбайливість)"
          ];
      
            // Массив приоритетов от 1 до 18
    const priorities = Array.from({ length: 18 }, (_, i) => i + 1);

    // Добавляем вопросы на страницу
    function renderQuestions() {
      const questionsContainer = document.getElementById('questionsContainer');

      questions.forEach((question, index) => {
        const questionDiv = document.createElement('div');
        questionDiv.classList.add('col-md-6', 'offset-md-3', 'question');

        // Создаем HTML для каждого вопроса
        const questionHtml = `
          <div class="question_name">
            <h6>${index + 1}. ${question}</h6>
          </div>
          <div class="answers_list">
            <select class="form-select form-select-sm priority-select" aria-label="Select priority">
              <option selected disabled>Оберіть відповідь</option>
              ${priorities.map(priority => `<option value="${priority}">${priority}</option>`).join('')}
            </select>
          </div>
        `;
        questionDiv.innerHTML = questionHtml;
        questionsContainer.appendChild(questionDiv);
      });

      // Находим все выпадающие списки с приоритетами
      const selectElements = document.querySelectorAll('.priority-select');

      // Функция обновления опций для всех выпадающих списков
      function updateOptions() {
        // Массив выбранных значений
        const selectedValues = Array.from(selectElements)
          .map(select => parseInt(select.value))
          .filter(val => !isNaN(val));

        // Обновляем каждое выпадающее меню
        selectElements.forEach(select => {
          const currentValue = parseInt(select.value);
          select.innerHTML = ''; // Очищаем опции

          // Добавляем первую опцию "Оберіть відповідь"
          const defaultOption = document.createElement('option');
          defaultOption.text = 'Оберіть відповідь';
          defaultOption.disabled = true;
          defaultOption.selected = isNaN(currentValue);
          select.appendChild(defaultOption);

          // Добавляем остальные опции, исключая уже выбранные
          priorities.forEach(priority => {
            if (!selectedValues.includes(priority) || priority === currentValue) {
              const option = document.createElement('option');
              option.value = priority;
              option.text = priority;
              if (priority === currentValue) {
                option.selected = true;
              }
              select.appendChild(option);
            }
          });
        });
      }

      // Обработчик событий для изменения приоритета
      selectElements.forEach(select => {
        select.addEventListener('change', updateOptions);
      });

      // Инициализация опций при загрузке страницы
      updateOptions();
    }

    // Вызываем функцию для отображения вопросов при загрузке страницы
    document.addEventListener('DOMContentLoaded', renderQuestions);
        
    }
})();

// Функція створення запитань для Модуля 5
// (function () {
//     'use strict'
  
//     if (currentPage === 'module5.html') {

//         const questions = [
//             "Активне діяльне життя (повнота і насиченість життя)",
//             "Життєва мудрість (зрілість суджень і здоровий глузд)",
//             "Здоров'я (фізичне і психічне)",
//             "Цікава робота",
//             "Краса природи та мистецтва (прекрасне в оточуючому світі)",
//             "Любов (духовна і фізична близкість з коханою людиною)",
//             "Матеріально забеспечене життя (відсутність матеріальних труднощів)",
//             "Наявність гарних і вірних друзів",
//             "Громадське визнання (повага оточення, колективу)",
//             "Пізнання (освіта, кругозір, загальна культура, інтелектуальний розвиток)",
//             "Продуктивне життя (максимальне використання своїх можливостей, сил і здібностей)",
//             "Розвиток (робота над собою, постійне вдосконалення)",
//             "Розваги (приємне проведення часу, відсутність обов'язків)",
//             "Свобода (самостійність, незалежність в судженнях і вчинках)",
//             "Щастливе сімейне життя",
//             "Щастя інших (розвиток та вдосконалення інших людей, людства в цілому)",
//             "Творчість (можливість творчої діяльности)",
//             "Впевненність в собі (внутрішня гармонія, свобода від вагань)"
//           ];
      
//             // Массив приоритетов от 1 до 18
//     const priorities = Array.from({ length: 18 }, (_, i) => i + 1);

//     // Добавляем вопросы на страницу
//     function renderQuestions() {
//       const questionsContainer = document.getElementById('questionsContainer');

//       questions.forEach((question, index) => {
//         const questionDiv = document.createElement('div');
//         questionDiv.classList.add('col-md-6', 'offset-md-3', 'question');

//         // Создаем HTML для каждого вопроса
//         const questionHtml = `
//           <div class="question_name">
//             <h6>${index + 1}. ${question}</h6>
//           </div>
//           <div class="answers_list">
//             <select class="form-select form-select-sm priority-select" aria-label="Select priority">
//               <option selected disabled>Оберіть відповідь</option>
//               ${priorities.map(priority => `<option value="${priority}">${priority}</option>`).join('')}
//             </select>
//           </div>
//         `;
//         questionDiv.innerHTML = questionHtml;
//         questionsContainer.appendChild(questionDiv);
//       });

//       // Находим все выпадающие списки с приоритетами
//       const selectElements = document.querySelectorAll('.priority-select');

//       // Функция обновления опций для всех выпадающих списков
//       function updateOptions() {
//         // Массив выбранных значений
//         const selectedValues = Array.from(selectElements)
//           .map(select => parseInt(select.value))
//           .filter(val => !isNaN(val));

//         // Обновляем каждое выпадающее меню
//         selectElements.forEach(select => {
//           const currentValue = parseInt(select.value);
//           select.innerHTML = ''; // Очищаем опции

//           // Добавляем первую опцию "Оберіть відповідь"
//           const defaultOption = document.createElement('option');
//           defaultOption.text = 'Оберіть відповідь';
//           defaultOption.disabled = true;
//           defaultOption.selected = isNaN(currentValue);
//           select.appendChild(defaultOption);

//           // Добавляем остальные опции, исключая уже выбранные
//           priorities.forEach(priority => {
//             if (!selectedValues.includes(priority) || priority === currentValue) {
//               const option = document.createElement('option');
//               option.value = priority;
//               option.text = priority;
//               if (priority === currentValue) {
//                 option.selected = true;
//               }
//               select.appendChild(option);
//             }
//           });
//         });
//       }

//       // Обработчик событий для изменения приоритета
//       selectElements.forEach(select => {
//         select.addEventListener('change', updateOptions);
//       });

//       // Инициализация опций при загрузке страницы
//       updateOptions();
//     }

//     // Вызываем функцию для отображения вопросов при загрузке страницы
//     document.addEventListener('DOMContentLoaded', renderQuestions);
        
//     }
// })();
// Функція створення запитань для Модуля 5 (нова версія)
(function () {
    'use strict'

    if (currentPage === 'module5.html') {

        const questions = [
            "Мені легко висловлювати свої думки в усній або письмовій формі.",
            "Я люблю розв’язувати головоломки та логічні задачі.",
            "Мені подобається малювати, ліпити, створювати візуальні образи.",
            "Я часто помічаю звуки, ритми, мелодії навколо себе.",
            "Я люблю займатися спортом, танцями або іншою рухливою активністю.",
            "Мені легко налагоджувати контакт з іншими людьми.",
            "Я часто розмірковую про сенс життя та свої цілі.",
            "Я відчуваю особливий зв’язок із природою та люблю бувати на свіжому повітрі.",
            "Я легко запам’ятовую нові слова, фрази, цікаві вислови.",
            "Мені приносить задоволення працювати з цифрами, графіками, формулами.",
            "Мені цікаво щось проєктувати, креслити або створювати своїми руками.",
            "Я чутливий до інтонацій, легко визначаю настрій за голосом.",
            "Я легко запам’ятовую рухи — у танцях, спорті чи побуті.",
            "Я розумію емоції інших людей і швидко вловлюю їхній настрій.",
            "Я люблю залишатися наодинці та розмірковувати про себе.",
            "Мене цікавлять рослини, тварини та природні явища.",
            "Я добре переказую почуті або прочитані історії.",
            "Я люблю шукати закономірності, систематизувати інформацію.",
            "Я мислю «картинками» і легко уявляю предмети в просторі.",
            "Музика займає важливе місце у моєму житті.",
            "Я добре контролюю своє тіло та рухи.",
            "Я вмію працювати в команді та бути лідером, коли потрібно.",
            "Я веду щоденник або нотую свої думки, часто аналізую себе.",
            "Мене хвилюють питання екології, зміни клімату, захист тварин.",
            "Я із задоволенням читаю, пишу тексти або беру участь в обговореннях."
        ];

        const scale = [
            { value: 1, text: "1 – зовсім не про мене" },
            { value: 2, text: "2 – скоріше не про мене" },
            { value: 3, text: "3 – частково про мене" },
            { value: 4, text: "4 – схоже на мене" },
            { value: 5, text: "5 – точно про мене" }
        ];

        function renderQuestions() {
            const questionsContainer = document.getElementById('questionsContainer');

            questions.forEach((question, index) => {
                const questionDiv = document.createElement('div');
                questionDiv.classList.add('col-md-6', 'offset-md-3', 'question');

                const questionTitle = document.createElement('div');
                questionTitle.classList.add('question_name');
                questionTitle.innerHTML = `<h6>${index + 1}. ${question}</h6>`;
                questionDiv.appendChild(questionTitle);

                const answersList = document.createElement('div');
                answersList.classList.add('answers_list');

                const select = document.createElement('select');
                select.classList.add('form-select', 'form-select-sm');

                const defaultOption = document.createElement('option');
                defaultOption.text = 'Оберіть оцінку';
                defaultOption.disabled = true;
                defaultOption.selected = true;
                select.appendChild(defaultOption);

                scale.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.value;
                    option.text = item.text;
                    select.appendChild(option);
                });

                answersList.appendChild(select);
                questionDiv.appendChild(answersList);
                questionsContainer.appendChild(questionDiv);
            });
        }

        document.addEventListener('DOMContentLoaded', renderQuestions);
    }
})();


// Функция создания вопросов для Модуля 6
(function () {
    'use strict'
  
    if (currentPage === 'module6.html') {
  
        let questions = [
            "Рівність (рівні можливості для всіх)",
            "Внутрішня гармонія (бути у мирі з самим собою)",
            "Соціальна сила (контроль над іншими, домінантність)",
            "Задоволення (реалізація власних бажань)",
            "Свобода (свобода думок і дій)",
            "Духовне життя (акцент на духовних, а не матеріальних питаннях)",
            "Відчуття приналежності (відчуття, що інші піклуються про мене)",
            "Соціальний порядок (стабільність суспільства)",
            "Життя, сповнене вражень (потяг до нового)",
            "Сенс життя (мета в житті)",
            "Вдячність (запобігливість, хороші манери)",
            "Багатство (матеріальна власність, гроші)",
            "Національна безпека (захищеність своєї нації від ворогів)",
            "Самоповага (віра у власну цінність)",
            "Поваги думки інших (врахування інтересів інших людей, відмова від конфронтації)",
            "Креативність (унікальність, багата уява)",
            "Мир у всьому світі (свобода від війн та конфліктів)",
            "Поваги традицій (збереження традицій, звичаїв)",
            "Зріле кохання (глибока емоційна і духовна близькість)",
            "Самодисципліна (самообмеження, стійкість до спокус)",
            "Право на самоту (особистий простір)",
            "Безпека сім'ї (безпека для близьких)",
            "Соціальне визнання (схвалення, повага інших)",
            "Єдність з природою (злиття з природою)",
            "Мінливе життя (життя, наповнене новизною і змінами)",
            "Мудрість (зріле розуміння світу)",
            "Авторитет (право бути лідером або командувати)",
            "Справжня дружба (близькі друзі)",
            "Світ краси (краса природи і мистецтва)",
            "Соціальна справедливість (виправлення несправедливості, турбота про слабких)",
            "Самостійний (що сподівається на себе, самодостатній)",
            "Стриманий (що уникає крайнощів в почуттях і діях)",
            "Вірний (відданий друзям, групі людей)",
            "Цілеспрямований (трудолюбивий, натхненний)",
            "Відкритий до чужої думки (терплячий до різних ідей і вірувань)",
            "Скромний (простий, не прагнучий притягнути до себе увагу)",
            "Сміливий (що шукає пригод, ризик)",
            "Що захищає довкілля (зберігає природу)",
            "Впливовий (що має вплив на людей і події)",
            "Повагаючий батьків і старших (виявляє повагу)",
            "Той, хто обирає власну мету (власний намір)",
            "Здоровий (не хворий фізично або душевно)",
            "Здібний (компетентний, здібний ефективно вирішувати питання)",
            "Приймаючий життя (підпорядковується життєвим обставинам)",
            "Чесний (відвертий, відкритий)",
            "Зберігаючий свій імідж (захист власного «обличчя»)",
            "Слухняний (той, хто виконує, підпорядковується правилам)",
            "Розумний (логічний, той що мислить)",
            "Корисний (працюючий на благо інших)",
            "Той, хто насолоджується життям (насолода їжею, спілкуванням, розвагами та ін.)",
            "Благочестивий (той, хто дотримується релігійної віри і переконань)",
            "Відповідальний (той, хто заслуговує на довіру)",
            "Допитливий (той, хто цікавиться всім)",
            "Схильний прощати (готовий прощати іншого)",
            "Успішний (хто досягає мети)",
            "Охайний (аккуратний)",
            "Хто потурає своїм бажанням (займається тим, що приносить задоволення)"
        ];
    
        function createQuestionElement(questionText, questionNumber) {
            let questionWrapper = document.createElement('div');
            questionWrapper.className = 'col-md-6 offset-md-3';
    
            let questionDiv = document.createElement('div');
            questionDiv.className = 'question';
    
            let questionNameDiv = document.createElement('div');
            questionNameDiv.className = 'question_name';
            
            let questionTitle = document.createElement('h6');
            questionTitle.textContent = `${questionNumber}. ${questionText}`;
    
            questionNameDiv.appendChild(questionTitle);
    
            let answersListDiv = document.createElement('div');
            answersListDiv.className = 'answers_list';
    
            let selectElement = document.createElement('select');
            selectElement.className = 'form-select form-select-sm';
            selectElement.setAttribute('aria-label', '.form-select-sm');
    
            let options = [
                { value: '', text: 'Оберіть відповідь' },
                { value: '7', text: '7' },
                { value: '6', text: '6' },
                { value: '5', text: '5' },
                { value: '4', text: '4' },
                { value: '3', text: '3' },
                { value: '2', text: '2' },
                { value: '1', text: '1' },
                { value: '0', text: '0' },
                { value: '-1', text: '-1' }
            ];
    
            options.forEach(option => {
            let optionElement = document.createElement('option');
            optionElement.value = option.value;
            optionElement.textContent = option.text;
            selectElement.appendChild(optionElement);
            });
    
            answersListDiv.appendChild(selectElement);
            questionDiv.appendChild(questionNameDiv);
            questionDiv.appendChild(answersListDiv);
            questionWrapper.appendChild(questionDiv);
    
            return questionWrapper;
        }
    
        function renderQuestions() {
            let questionsContainer = document.getElementById('questionsContainer');
            questions.forEach((question, index) => {
            let questionElement = createQuestionElement(question, index + 1);
            questionsContainer.appendChild(questionElement);
            });
        }
    
        // Вызов функции для генерации вопросов
        renderQuestions();
  
    }
})();

// Функція створення запитань для Модуля 7
(function () {
    'use strict'
  
    if (currentPage === 'module7.html') {
  
      let questions = [
        {
        question: "1.",
        answers: ["інженер-технік", "контролер"]
        },
        {
        question: "2.",
        answers: ["модельер, вʼязальник", "санітарний лікар"]
        },
        {
        question: "3.",
        answers: ["кухар", "секретар, копірайтер"]
        },
        {
        question: "4.",
        answers: ["фотограф", "головний менеджер магазину"]
        },
        {
        question: "5.",
        answers: ["конструктор-кресляр", "дизайнер"]
        },
        {
        question: "6.",
        answers: ["філософ", "психіатр"]
        },
        {
        question: "7.",
        answers: ["вчений-хімік", "аудитор, бухгалтер"]
        },
        {
        question: "8.",
        answers: ["редактор журналу", "адвокат"]
        },
        {
        question: "9.",
        answers: ["лінгвіст", "перекладач художньої літератури"]
        },
        {
        question: "10.",
        answers: ["педіатр", "аналітик даних"]
        },
        {
        question: "11.",
        answers: ["вихователь", "керівник відділу кадрів"]
        },
        {
        question: "12.",
        answers: ["спортивний лікар", "поет"]
        },
        {
        question: "13.",
        answers: ["нотаріус", "менеджер по закупівлям"]
        },
        {
        question: "14.",
        answers: ["робітник-будівельник", "художник-карікатурист"]
        },
        {
        question: "15.",
        answers: ["політик", "письменник"]
        },
        {
        question: "16.",
        answers: ["садівник, ландшафтний дизайнер", "метеоролог"]
        },
        {
        question: "17.",
        answers: ["водій", "медсестра/медбрат"]
        },
        {
        question: "18.",
        answers: ["інженер-електрик", "секретар, копірайтер"]
        },
        {
        question: "19.",
        answers: ["маляр, декоратор", "зварювальник, художник по металу"]
        },
        {
        question: "20.",
        answers: ["біолог", "головний лікар"]
        },
        {
        question: "21.",
        answers: ["відеооператор", "режисер"]
        },
        {
        question: "22.",
        answers: ["гідролог, дослідник водних ресурсів", "інспектор"]
        },
        {
        question: "23.",
        answers: ["ветеринар", "зоолог свійських тварин"]
        },
        {
        question: "24.",
        answers: ["математик", "архітектор"]
        },
        {
        question: "25.",
        answers: ["працівник поліції", "аналітик"]
        },
        {
        question: "26.",
        answers: ["вихователь", "художник"]
        },
        {
        question: "27.",
        answers: ["копірайтер", "критик"]
        },
        {
        question: "28.",
        answers: ["менеджер", "керівник"]
        },
        {
        question: "29.",
        answers: ["радіоінженер", "ядерний фізик"]
        },
        {
        question: "30.",
        answers: ["модельєр-дизайнер", "декоратор"]
        },
        {
        question: "31.",
        answers: ["археолог", "експерт"]
        },
        {
        question: "32.",
        answers: ["мистецтвознавець", "консультант"]
        },
        {
        question: "33.",
        answers: ["логопед", "тестувальник"]
        },
        {
        question: "34.",
        answers: ["лікар", "дипломат"]
        },
        {
        question: "35.",
        answers: ["головний бухгалтер", "директор"]
        },
        {
        question: "36.",
        answers: ["поет", "психолог"]
        },
        {
        question: "37.",
        answers: ["програміст", "скульптор"]
        }
      ];
  
      let questionsContainer = document.getElementById('questionsContainer');
  
      questions.forEach((item, index) => {
          let questionDiv = document.createElement('div');
          questionDiv.classList.add('col-md-6', 'offset-md-3', 'mb-4');
  
          questionDiv.innerHTML = `
              <div class="question">
                  <div class="question_name">
                      <h6>${item.question}</h6>
                  </div>
                  <div class="answers_list">
                      ${item.answers.map((answer, i) => `
                          <div class="form-check">
                              <input class="form-check-input" type="radio" name="question_${index + 1}" id="q${index + 1}a${i + 1}">
                              <label class="form-check-label" for="q${index + 1}a${i + 1}">
                                  ${answer}
                              </label>
                          </div>
                      `).join('')}
                  </div>
              </div>
          `;
  
          questionsContainer.appendChild(questionDiv);
      });
  
    }
  
})();

// Функція створення запитань для Модуля 8
(function () {
    'use strict';

    if (currentPage === 'module8.html') {
        const questions = [
            {
                question: "Коли я приймаю рішення, я...",
                answers: [
                    "спираюсь на свої відчуття",
                    "обираю те, що звучить найкраще",
                    "обираю те, що виглядає для мене найкращим чином",
                    "спираюсь на точність інформації і зібрані дані по цьому питанню"
                ]
            },
            {
                question: "В процесі обговорення на мене скоріш за все вплине ...",
                answers: [
                    "інтонація і ораторські здібності іншої людини",
                    "можливість бачити схеми і презентацію",
                    "логічність аргументів",
                    "можливість відчувати емоційний стан людини"
                ]
            },
            {
                question: "Те, що відбувається зі мною легше за все пояснити ...",
                answers: [
                    "моїм зовнішнім виглядом, як я одягаюсь",
                    "моїми почуттями і внутрішнім світом",
                    "тим, що я говорю і пояснюю",
                    "тембром мого голосу і здатністю переконувати"
                ]
            },
            {
                question: "Мені легше за все ...",
                answers: [
                    "налаштувати звук, знайти правильний тембр, почути тональність",
                    "правильно сформулювати питання на будь-яку тему",
                    "обрати комфортні меблі",
                    "підібрати вдалу комбінацію фарб і відтінків"
                ]
            },
            {
                question: "В мене виходить без зайвих зусиль ...",
                answers: [
                    "налаштуватися і почути звуки, мелодії, вловити тональність оточення",
                    "надавати сенс і зміст новим фактам, новим явищам",
                    "підібрати вдалий і ефектний одяг, створити власний образ",
                    "створити атмосферу в кімнаті, щоб люди відчували себе особливо"
                ]
            }
        ];

        const questionsContainer = document.getElementById('questionsContainer');

        const ratingOptions = [4, 3, 2, 1];

        questions.forEach((item, index) => {
            const questionDiv = document.createElement('div');
            questionDiv.classList.add('col-md-6', 'offset-md-3', 'mb-4', 'question');

            const questionTitle = document.createElement('div');
            questionTitle.classList.add('question_name');
            questionTitle.innerHTML = `<h6>${index + 1}. ${item.question}</h6>`;
            questionDiv.appendChild(questionTitle);

            const answersList = document.createElement('div');
            answersList.classList.add('answers_list');

            const selects = [];

            item.answers.forEach(answer => {
                const answerItem = document.createElement('div');
                answerItem.classList.add('answer-item', 'd-flex', 'align-items-center', 'mb-2');

                const answerText = document.createElement('span');
                answerText.classList.add('me-3');
                answerText.textContent = `• ${answer}`;

                const select = document.createElement('select');
                select.classList.add('form-select', 'form-select-sm');
                selects.push(select); // сохраняем селект

                answerItem.appendChild(answerText);
                answerItem.appendChild(select);
                answersList.appendChild(answerItem);
            });

            questionDiv.appendChild(answersList);
            questionsContainer.appendChild(questionDiv);

            // Функция обновления опций
            function updateSelectOptions() {
                const selectedValues = selects
                    .map(sel => parseInt(sel.value))
                    .filter(val => !isNaN(val));

                selects.forEach(sel => {
                    const currentValue = parseInt(sel.value);
                    sel.innerHTML = '';

                    const defaultOption = document.createElement('option');
                    defaultOption.text = 'Оцінка';
                    defaultOption.disabled = true;
                    defaultOption.selected = isNaN(currentValue);
                    sel.appendChild(defaultOption);

                    ratingOptions.forEach(optionValue => {
                        if (!selectedValues.includes(optionValue) || optionValue === currentValue) {
                            const option = document.createElement('option');
                            option.value = optionValue;
                            option.text = optionValue;
                            if (optionValue === currentValue) {
                                option.selected = true;
                            }
                            sel.appendChild(option);
                        }
                    });
                });
            }

            selects.forEach(sel => {
                sel.addEventListener('change', updateSelectOptions);
            });

            updateSelectOptions();
        });
    }

})();


// Функция для отправки данных формы и отправки email
(function () {
    'use strict';

    document.getElementById('sendQiuz').addEventListener('click', (e) => {
        e.preventDefault();  // Останавливаем стандартное действие кнопки отправки

        // Получаем данные из localStorage
        const userName = localStorage.getItem('userName');
        const userSurname = localStorage.getItem('userSurname');
        const userEmail = localStorage.getItem('userEmail');
        const userBirthday = localStorage.getItem('userBirthday');
        const userAnswers = JSON.parse(localStorage.getItem('allAnswers')) || [];

        // Инициализируем массив для хранения данных модулей
        const modulesData = [];

        // Формируем массив модулей с ответами
        userAnswers.forEach(module => {
            modulesData.push({
                modul_name: module.moduleName,
                answers: module.answers
            });
        });

        // Объединяем все данные в одном объекте
        const data = {
            name: userName,
            surname: userSurname,
            email: userEmail,
            birthday: userBirthday,
            answer: JSON.stringify(modulesData)
        };

        console.log(data.answer);

        // Отправляем данные в базу данных, а затем отправляем email
        sendForm(data);
    });

    async function sendForm(data) {
        try {
            // Сначала отправляем данные в save_answers.php
            const res = await fetch('./save_answers.php', {
                method: 'POST',
                headers: {'Content-type': 'application/json'},
                body: JSON.stringify(data)
            });

            if (res.ok) {
                // Если данные успешно сохранены, отправляем email
                await sendEmail(data); 
                // Перенаправление на thank.html после отправки email
                window.location.href = 'thank.html'; 
            } else {
                alert('Ошибка при сохранении данных');
            }
        } catch (error) {
            console.error('Ошибка:', error);
            alert('Ошибка сети');
        }
    }

    async function sendEmail(data) {
        try {
            const emailRes = await fetch('./send_email.php', {
                method: 'POST',
                headers: {'Content-type': 'application/json'},
                body: JSON.stringify({
                    name: data.name,
                    surname: data.surname,
                    email: data.email
                })
            });

            const emailResult = await emailRes.json();
            if (emailResult.status === 'success') {
                console.log('Email успешно отправлен!');
            } else {
                console.error('Ошибка отправки email:', emailResult.message);
            }
        } catch (error) {
            console.error('Ошибка при отправке email:', error);
        }
    }
})();



 



    
    
    




