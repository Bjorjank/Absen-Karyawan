<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-scroll shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (Auth::check())
                        {{ __("You're logged in, ") }} {{ Auth::user()->name }}! <br>
                    @endif
                    <h1 class="text-center">Silahkan Absen</h1>
             
                    <form method="POST" action="{{ route('attendance.checkin') }}" enctype="multipart/form-data" id="attendanceForm">
                        @csrf
                        <input type="hidden" name="employee_id" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="check_in" value="{{ now() }}">
                        
                        <!-- Video element for displaying camera stream -->
                        <video id="video" width="320" height="240" autoplay></video>
                        
                        <!-- Hidden canvas element for capturing photo -->
                        <canvas id="canvas" style="display: none;"></canvas>
                        
                        <!-- Hidden input field for storing photo data -->
                        <input type="file" name="photo" id="photo" style="display: none;">
                        
                        <input type="hidden" id="latitude" name="latitude">
                        <input type="hidden" id="longitude" name="longitude">
                    
                        <div class="mb-4">
                            <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status:</label>
                            <select name="status" id="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                <option value="hadir">Hadir</option>
                                <option value="izin">Izin</option>
                                <option value="sakit">Sakit</option>
                                <option value="cuti">Cuti</option>
                            </select>
                        </div>
                    
                        <div class="mb-4" id="alasanInput" style="display: none;">
                            <label for="alasan" class="block text-gray-700 text-sm font-bold mb-2">Alasan Izin:</label>
                            <input type="text" name="alasan" id="alasan" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <button type="button" id="takePhotoButton" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mt-3 text-center">Take Photo</button>
                
                        <button type="button" onclick="getLocationAndSubmit()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Silahkan Absen
                        </button>
                    </form>
                    
                    @if(Auth::user() && Auth::user()->is_admin == 1)
                    <h1 class="text-center">Admin</h1>
                    <a href="{{route('employees.create')}}">click</a>
                    <br><br><br>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Include jQuery for functionality -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const photoInput = document.getElementById('photo');
        const takePhotoButton = document.getElementById('takePhotoButton');
        
        // Access the camera
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
                video.srcObject = stream;
                video.play();
            }).catch(function(error) {
                console.error('Error accessing the camera:', error);
            });
        } else {
            console.error('Media devices API not supported.');
        }

        // Capture photo from video stream
        takePhotoButton.addEventListener('click', function() {
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            canvas.toBlob(function(blob) {
                const file = new File([blob], "photo.png", { type: "image/png" });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                document.getElementById('photo').files = dataTransfer.files;
                alert('Photo captured successfully!');
            }, 'image/png');
        });
    });

    $(document).ready(function() {
        $('#status').change(function() {
            var selectedStatus = $(this).val();
            if (selectedStatus !== 'hadir') {
                $('#alasanInput').show();
                $('#alasan').prop('required', true);
            } else {
                $('#alasanInput').hide();
                $('#alasan').prop('required', false);
            }
        });
    });

    function getLocationAndSubmit() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                document.getElementById("latitude").value = position.coords.latitude;
                document.getElementById("longitude").value = position.coords.longitude;
                // Submit form secara manual setelah mendapatkan lokasi
                document.getElementById('attendanceForm').submit();
            });
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }
</script>

</x-app-layout>
