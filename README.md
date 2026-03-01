Witam, chciałbym na początku napisać dlaczego wybrałem framework Symfony zamiast Laravela - wybrałem Symfony ponieważ pracuje z nim na co dzień i znam go dobrze, w zadaniu został postawiony limit czasowy więc pomyślałem że zrobie to szybciej w frameworku który znam bardziej. 
Prosze o wyrozumiałość w tej kwestii. Nie będzie dla mnie problemem już w pracy przejście na Laravela.

W jaki sposób uruchomić aplikacje?:
- Wybieramy miejsce gdzie ma być apka i w cmd: git clone https://github.com/Tomataelo/pokeApp.git .
- Pobieramy dockera i stawiamy kontener poprzez wejście w apke i wklepaniu komendy: docker-compose up -d --build.
- I apka stoi.

W aplikacji korzystam z takich bibliotek jak np. serializer do wygodnego mapowania body requesta na obiekty DTO oraz na odwrót, używam także predis'a do wygodnego korzystania z redisa (jako cache), korzystam także z guzzle http jako klienta http do łączenia z poke api.

Moje endpointy to:

POST: /banned/ - tworzy zbanowanego pokemona i potrzeba tylko w jsonie pola name
GET: /banned/all - pobiera wszystkie zbanowane pokemony
DELETE: /banned/{name} - usuwa danego zbanowanego pokemona

Na potrzeby zadania dane zwracane z pokeApi to name,weight i height, nie widziałem sensu żeby zwracało całe dane ponieważ pokemony tworzone lokalnie też muszą mieć takie same pola a uzupełnianie tych pól to troche strata czasu była.

GET: /info/{pokemonNames} - pobiera info z bazy danych apki lub z pokeApi odnośnie imion danych pokemonów, pomija zbanowane pokemony oraz pole 'own' informuje czy jest to pokemon z poke api czy stworzony poprzez aplikacje

{identifier} - to id lub nazwa

POST: /pokemon/ - tworzy pokemona, potrzebne pola to: name, weight, height 
GET: /pokemon/{identifier} - pobiera danego pokemona z bazy apki
DELETE: /pokemon/{identifier} - usuwa danego pokemona z bazy apki
PUT: /pokemon/{identifier} - robi update dla danego pokemona z bazy apki, pola które można modyfikować to: name, weight, height
