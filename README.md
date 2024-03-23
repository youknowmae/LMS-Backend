<h1>Library Information Management System</h1>

<h5>Notes: </h5>
<ul>
    <li>Create .env to connect</li>
    <li>Migrate first before proceeding</li>
    <li>You can use seeders to use test data. Current available factory and seeders: users, departments, books, periodicals, articles, and projects</li>
    <li>Database structure is bound to change without further notice, kindly update version whenever possible</li>
    <li>Consult first before altering database structure</li>
</ul>
<h2>Routes</h2>
<em>Default URL: localhost:8000/api</em><br>
<em>Default return type is in json format</em>
<h3>All Materials (GET): </h3>
<ol>
    <li>/books</li>
    <li>/periodicals</li>
    <li>/articles</li>
    <li>/projects</li>
</ol>

<h3>Single Material (GET): </h3>
<ol>    
    <li>/books/{int:id}</li>
    <li>/periodical/{int:id}</li>
    <li>/article/{int:id}</li>
    <li>/project/{int:id}</li>
</ol>

<h3>Periodicals and Projects according to type (GET): </h3>
<ol>
    <li>/periodicals/{string:type}</li>
    <li>/projects/{string:type}</li>
</ol>

<hr>
<em><strong>Fillables</strong></em>
<pre>
    <em><b>Books:</b>
            'call_number': str, 'title': str, 'author': str, 'image_location': str, 'language': str,
            'location_id': int, 'publisher': str, 'copyright': year, 'volume': int(nullable), 'issue': int(nullable), 
            'pages': int, 'content': text(nullable), 'remarks': text(nullable), 'date_published': date
    </em>
</pre><br>

<pre>
    <em><b>Periodicals:</b>
            'material_type': str, 'title': str, 'author': str, 'image_location': str, 'language': str,
            'publisher': str, 'copyright': year, 'volume': int(nullable), 'issue': int(nullable), 
            'pages': int, 'content': text(nullable), 'remarks': text(nullable), 'date_published': date            
    </em>
</pre><br>

<pre>
    <em><b>Articles:</b>
            'title': str, 'author': str, 'language': str, 'subject': str, 'date_published': date,
            'volume': int(nullable), 'issue': int(nullable), 'page': int, 'abstract': str, 'remarks': str(nullable)
    </em>
</pre><br>

<pre>
    <em><b>Projects:</b>
            'type': str, 'title': str, 'author': str, 'course_id': int(FK), 'image_location': str, 
            'date_published': date, 'language': str, abstract': str
    </em>
</pre><br>

<h3>Adding Materials (POST): </h3>
<ol>    
    <li>/books/add</li>
    <li>/periodicals/add</li>
    <li>/articles/add</li>
    <li>/projects/add</li>
</ol>

<h3>Updating Materials (PUT, PATCH): </h3>
<ol>    
    <li>/books/update/{id}</li>
    <li>/periodicals/update/{id}</li>
    <li>/articles/update/{id}</li>
    <li>/projects/update/{id}</li>
</ol>

<h3>Delete Materials (DELETE): </h3>
<ol>    
    <li>/books/delete/{id}</li>
    <li>/periodicals/delete/{id}</li>
    <li>/articles/delete/{id}</li>
    <li>/projects/delete/{id}</li>
</ol>
