<x-filament-panels::page>
    @if ($this->student)
        <p>QR Code for: <strong>{{ $this->student->name }}</strong> <strong>{{ $this->student->email}}</strong></p>
        {!! QrCode::size(200)->generate($this->student->name) !!}
    @else
        <p>No student selected. Please choose a student.</p>
    @endif
</x-filament-panels::page>
