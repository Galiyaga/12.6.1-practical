<?php
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

//Разбиение и объединение ФИО
function getPartsFromFullname($fullname) {
    $parts = explode(' ', $fullname);
    return [
        'surname' => $parts[0],
        'name' => $parts[1],
        'patronymic' => $parts[2]
    ];
}

function getFullnameFromParts($surname, $name, $patronymic) {
    return "$surname $name $patronymic";
}

//пример использования
foreach ($example_persons_array as $person) {
    $parts = getPartsFromFullname($person['fullname']);
    $fullname = getFullnameFromParts($parts['surname'], $parts['name'], $parts['patronymic']);
}

//Сокращение ФИО
function getShortName($fullname) {
    $parts = getPartsFromFullname($fullname);
    $shortName = mb_substr($parts['name'], 0, 1, 'UTF-8') . '.';
    $result = $parts['surname'] . ' ' . $shortName;
    return $result;
}

//пример использования
$fullname = 'Иванов Иван Иванович';
$shortName = getShortName($fullname);
echo $shortName; 


//Функция определения пола по ФИО
function getGenderFromName($fullname) {
    $parts = getPartsFromFullname($fullname);
    $genderScore = 0;
    
    // Признаки женского пола:
    if (mb_substr($parts['patronymic'], -3, null, 'UTF-8') === 'вна') {
        $genderScore--;
    }
    if (mb_substr($parts['name'], -1, null, 'UTF-8') === 'а') {
        $genderScore--;
    }
    if (mb_substr($parts['surname'], -2, null, 'UTF-8') === 'ва') {
        $genderScore--;
    }
    
    // Признаки мужского пола:
    if (mb_substr($parts['patronymic'], -2, null, 'UTF-8') === 'ич') {
        $genderScore++;
    }
    if (mb_substr($parts['name'], -1, null, 'UTF-8') === 'й' || mb_substr($parts['name'], -1, null, 'UTF-8') === 'н') {
        $genderScore++;
    }
    if (mb_substr($parts['surname'], -1, null, 'UTF-8') === 'в') {
        $genderScore++;
    }
    
    if ($genderScore > 0) {
        return 1; // мужчина
    } elseif ($genderScore < 0) {
        return -1; // женщина
    } else {
        return 0; // Undefined
    }
}

//пример использования
$gender = getGenderFromName('Шварцнегер Арнольд Густавович');
echo $gender; 

//Определение возрастно-полового состава
function getGenderDescription($persons_array) {
    $total_count = count($persons_array);
    $male_count = 0;
    $female_count = 0;
    $undefined_count = 0;

    foreach ($persons_array as $person) {
        $gender = getGenderFromName($person['fullname']);
        if ($gender === 1) {
            $male_count++;
        } elseif ($gender === -1) {
            $female_count++;
        } else {
            $undefined_count++;
        }
    }

    $male_percentage = round(($male_count / $total_count) * 100, 1);
    $female_percentage = round(($female_count / $total_count) * 100, 1);
    $undefined_percentage = round(($undefined_count / $total_count) * 100, 1);

    $result = "Гендерный состав аудитории:\n";
    $result .= "---------------------------\n";
    $result .= "Мужчины - $male_percentage%\n";
    $result .= "Женщины - $female_percentage%\n";
    $result .= "Не удалось определить - $undefined_percentage%\n";

    return $result;
}

//пример использования
$gender_description = getGenderDescription($example_persons_array);
echo $gender_description;

//Идеальный подбор пары
function getPerfectPartner($last_name, $first_name, $patronymic, $persons_array) {
    $formatted_last_name = mb_convert_case($last_name, MB_CASE_TITLE, 'UTF-8');
    $formatted_first_name = mb_convert_case($first_name, MB_CASE_TITLE, 'UTF-8');
    $formatted_patronymic = mb_convert_case($patronymic, MB_CASE_TITLE, 'UTF-8');

    $full_name = getFullnameFromParts($formatted_last_name, $formatted_first_name, $formatted_patronymic);

    $gender = getGenderFromName($full_name);

    $suitable_partners = array_filter($persons_array, function ($person) use ($gender) {
        $person_gender = getGenderFromName($person['fullname']);
        return $person_gender === -$gender;
    });

    $random_partner = $suitable_partners[array_rand($suitable_partners)];

    $compatibility_percentage = round(mt_rand(5000, 10000) / 100, 2);

    $partner_short_name = getShortName($random_partner['fullname']);

    $result = $full_name . ' + ' . $partner_short_name . ' = ' . PHP_EOL;
    $result .= '♡ Идеально на ' . $compatibility_percentage . '% ♡';

    return $result;
}

$last_name = 'ИвАНОВ';
$first_name = 'ИВАН';
$patronymic = 'иВАНОВИЧ';

$perfect_partner = getPerfectPartner($last_name, $first_name, $patronymic, $example_persons_array);
echo $perfect_partner;