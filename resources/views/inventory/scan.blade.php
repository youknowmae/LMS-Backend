<!-- resources/views/inventory/scan.blade.php -->

<form action="{{ url('/inventory/scan') }}" method="post">
    @csrf
    <label for="barcode">Scan Barcode:</label>
    <input type="text" id="barcode" name="barcode" autofocus>
    <button type="submit">Submit</button>
</form>
