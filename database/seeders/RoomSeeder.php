<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = [
            // General Classrooms
            [
                'name' => 'Classroom 101',
                'code' => 'CR-101',
                'type' => 'general',
                'capacity' => 40,
                'location' => '1st Floor, Building A',
                'equipment' => ['Whiteboard', 'Projector', 'Air Conditioning'],
                'is_available' => true,
                'special_requirements' => null,
                'notes' => 'Standard classroom with basic amenities'
            ],
            [
                'name' => 'Classroom 102',
                'code' => 'CR-102',
                'type' => 'general',
                'capacity' => 35,
                'location' => '1st Floor, Building A',
                'equipment' => ['Whiteboard', 'Projector', 'Air Conditioning'],
                'is_available' => true,
                'special_requirements' => null,
                'notes' => 'Standard classroom with basic amenities'
            ],
            [
                'name' => 'Classroom 201',
                'code' => 'CR-201',
                'type' => 'general',
                'capacity' => 45,
                'location' => '2nd Floor, Building A',
                'equipment' => ['Whiteboard', 'Projector', 'Air Conditioning'],
                'is_available' => true,
                'special_requirements' => null,
                'notes' => 'Larger classroom for bigger classes'
            ],
            [
                'name' => 'Classroom 202',
                'code' => 'CR-202',
                'type' => 'general',
                'capacity' => 30,
                'location' => '2nd Floor, Building A',
                'equipment' => ['Whiteboard', 'Projector', 'Air Conditioning'],
                'is_available' => true,
                'special_requirements' => null,
                'notes' => 'Smaller classroom for specialized subjects'
            ],

            // Computer Laboratories
            [
                'name' => 'Computer Laboratory 1',
                'code' => 'CL-001',
                'type' => 'computer_lab',
                'capacity' => 30,
                'location' => '2nd Floor, Building B',
                'equipment' => ['Desktop Computers', 'Projector', 'Air Conditioning', 'Network Switch'],
                'is_available' => true,
                'special_requirements' => ['Computer Lab', 'ICT'],
                'notes' => 'Fully equipped computer lab with 30 workstations'
            ],
            [
                'name' => 'Computer Laboratory 2',
                'code' => 'CL-002',
                'type' => 'computer_lab',
                'capacity' => 25,
                'location' => '2nd Floor, Building B',
                'equipment' => ['Desktop Computers', 'Projector', 'Air Conditioning', 'Network Switch'],
                'is_available' => true,
                'special_requirements' => ['Computer Lab', 'ICT'],
                'notes' => 'Computer lab for advanced programming classes'
            ],

            // Science Laboratories
            [
                'name' => 'Chemistry Laboratory',
                'code' => 'SL-001',
                'type' => 'science_lab',
                'capacity' => 24,
                'location' => '1st Floor, Building C',
                'equipment' => ['Lab Tables', 'Safety Equipment', 'Fume Hood', 'Chemical Storage'],
                'is_available' => true,
                'special_requirements' => ['Science Lab', 'Chemistry'],
                'notes' => 'Fully equipped chemistry laboratory with safety features'
            ],
            [
                'name' => 'Physics Laboratory',
                'code' => 'SL-002',
                'type' => 'science_lab',
                'capacity' => 20,
                'location' => '1st Floor, Building C',
                'equipment' => ['Lab Tables', 'Physics Equipment', 'Measuring Instruments'],
                'is_available' => true,
                'special_requirements' => ['Science Lab', 'Physics'],
                'notes' => 'Physics laboratory with specialized equipment'
            ],
            [
                'name' => 'Biology Laboratory',
                'code' => 'SL-003',
                'type' => 'science_lab',
                'capacity' => 22,
                'location' => '1st Floor, Building C',
                'equipment' => ['Lab Tables', 'Microscopes', 'Specimen Storage', 'Safety Equipment'],
                'is_available' => true,
                'special_requirements' => ['Science Lab', 'Biology'],
                'notes' => 'Biology laboratory with microscopes and specimen storage'
            ],

            // Special Rooms
            [
                'name' => 'Library',
                'code' => 'LIB-001',
                'type' => 'library',
                'capacity' => 50,
                'location' => 'Ground Floor, Building A',
                'equipment' => ['Bookshelves', 'Reading Tables', 'Computers', 'Air Conditioning'],
                'is_available' => true,
                'special_requirements' => ['Library', 'Research'],
                'notes' => 'Main library with study areas and computer access'
            ],
            [
                'name' => 'Gymnasium',
                'code' => 'GYM-001',
                'type' => 'gymnasium',
                'capacity' => 100,
                'location' => 'Ground Floor, Building D',
                'equipment' => ['Basketball Court', 'Volleyball Court', 'Sports Equipment'],
                'is_available' => true,
                'special_requirements' => ['PE', 'Physical Education'],
                'notes' => 'Multi-purpose gymnasium for sports and physical education'
            ],
            [
                'name' => 'Auditorium',
                'code' => 'AUD-001',
                'type' => 'auditorium',
                'capacity' => 200,
                'location' => 'Ground Floor, Building A',
                'equipment' => ['Stage', 'Sound System', 'Lighting', 'Projector'],
                'is_available' => true,
                'special_requirements' => ['Events', 'Presentations'],
                'notes' => 'Large auditorium for events and presentations'
            ],

            // Additional Classrooms
            [
                'name' => 'Classroom 301',
                'code' => 'CR-301',
                'type' => 'general',
                'capacity' => 38,
                'location' => '3rd Floor, Building A',
                'equipment' => ['Whiteboard', 'Projector', 'Air Conditioning'],
                'is_available' => true,
                'special_requirements' => null,
                'notes' => 'Upper floor classroom with good ventilation'
            ],
            [
                'name' => 'Classroom 302',
                'code' => 'CR-302',
                'type' => 'general',
                'capacity' => 42,
                'location' => '3rd Floor, Building A',
                'equipment' => ['Whiteboard', 'Projector', 'Air Conditioning'],
                'is_available' => true,
                'special_requirements' => null,
                'notes' => 'Upper floor classroom with good ventilation'
            ],
            [
                'name' => 'Classroom 401',
                'code' => 'CR-401',
                'type' => 'general',
                'capacity' => 36,
                'location' => '4th Floor, Building A',
                'equipment' => ['Whiteboard', 'Projector', 'Air Conditioning'],
                'is_available' => true,
                'special_requirements' => null,
                'notes' => 'Top floor classroom with panoramic view'
            ],
            [
                'name' => 'Classroom 402',
                'code' => 'CR-402',
                'type' => 'general',
                'capacity' => 33,
                'location' => '4th Floor, Building A',
                'equipment' => ['Whiteboard', 'Projector', 'Air Conditioning'],
                'is_available' => true,
                'special_requirements' => null,
                'notes' => 'Top floor classroom with panoramic view'
            ]
        ];

        foreach ($rooms as $roomData) {
            Room::create($roomData);
        }
    }
}