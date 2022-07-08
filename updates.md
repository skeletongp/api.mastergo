# UPDATES

### 15/04/2022
Para colocar distintos nombres de página cuando hay dos datatables en la misma vista
`protected $queryString = [
'page' => ['except' => 1, 'as' => 'page_name'],
];
`
