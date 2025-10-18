import React, { useState } from 'react';

const SeatSelector = ({ selectedClass, passengers, onSeatsSelected }) => {
  const [selectedSeats, setSelectedSeats] = useState([]);

  // Génération de la carte des sièges selon la classe
  const generateSeats = () => {
    const rows = selectedClass === 'economy' ? 30 : selectedClass === 'business' ? 8 : 4;
    const seatsPerRow = selectedClass === 'economy' ? 6 : selectedClass === 'business' ? 4 : 2;
    
    const seats = [];
    const occupiedSeats = ['1A', '1B', '3C', '5D', '7A', '10B', '12E', '15C']; // Sièges déjà pris
    
    for (let row = 1; row <= rows; row++) {
      const rowSeats = [];
      const letters = selectedClass === 'economy' ? ['A', 'B', 'C', 'D', 'E', 'F'] : 
                      selectedClass === 'business' ? ['A', 'B', 'D', 'E'] : ['A', 'B'];
      
      letters.forEach(letter => {
        const seatId = `${row}${letter}`;
        rowSeats.push({
          id: seatId,
          row,
          letter,
          isOccupied: occupiedSeats.includes(seatId),
          isSelected: selectedSeats.includes(seatId)
        });
      });
      seats.push(rowSeats);
    }
    return seats;
  };

  const handleSeatClick = (seatId, isOccupied) => {
    if (isOccupied) return;
    
    if (selectedSeats.includes(seatId)) {
      const newSeats = selectedSeats.filter(s => s !== seatId);
      setSelectedSeats(newSeats);
      onSeatsSelected(newSeats);
    } else if (selectedSeats.length < passengers) {
      const newSeats = [...selectedSeats, seatId];
      setSelectedSeats(newSeats);
      onSeatsSelected(newSeats);
    }
  };

  const seats = generateSeats();

  return (
    <div className="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xl">
      <div className="flex items-center justify-between mb-6">
        <h2 className="text-2xl font-black">Sélection des Sièges</h2>
        <div className="text-sm text-gray-600 dark:text-gray-400">
          {selectedSeats.length}/{passengers} sélectionné(s)
        </div>
      </div>

      {/* Légende */}
      <div className="flex items-center justify-center space-x-6 mb-6 p-4 bg-gray-50 dark:bg-gray-900 rounded-xl">
        <div className="flex items-center space-x-2">
          <div className="w-8 h-8 bg-gray-200 dark:bg-gray-700 rounded-lg"></div>
          <span className="text-sm">Disponible</span>
        </div>
        <div className="flex items-center space-x-2">
          <div className="w-8 h-8 bg-purple-600 rounded-lg"></div>
          <span className="text-sm">Sélectionné</span>
        </div>
        <div className="flex items-center space-x-2">
          <div className="w-8 h-8 bg-red-400 rounded-lg"></div>
          <span className="text-sm">Occupé</span>
        </div>
      </div>

      {/* Plan de l'avion */}
      <div className="max-h-96 overflow-y-auto">
        <div className="space-y-2">
          {seats.map((row, rowIndex) => (
            <div key={rowIndex} className="flex items-center justify-center space-x-2">
              <span className="w-8 text-center text-sm font-bold text-gray-500">
                {row[0].row}
              </span>
              {row.map((seat, seatIndex) => (
                <React.Fragment key={seat.id}>
                  <button
                    onClick={() => handleSeatClick(seat.id, seat.isOccupied)}
                    disabled={seat.isOccupied}
                    className={`w-10 h-10 rounded-lg font-bold text-xs transition-all ${
                      seat.isOccupied
                        ? 'bg-red-400 cursor-not-allowed text-white'
                        : seat.isSelected
                        ? 'bg-purple-600 text-white scale-110 shadow-lg'
                        : 'bg-gray-200 dark:bg-gray-700 hover:bg-purple-200 dark:hover:bg-purple-900'
                    }`}
                  >
                    {seat.letter}
                  </button>
                  {/* Allée centrale */}
                  {(selectedClass === 'economy' && seatIndex === 2) ||
                   (selectedClass === 'business' && seatIndex === 1) ? (
                    <div className="w-4"></div>
                  ) : null}
                </React.Fragment>
              ))}
            </div>
          ))}
        </div>
      </div>

      {selectedSeats.length > 0 && (
        <div className="mt-6 p-4 bg-purple-50 dark:bg-purple-900/20 rounded-xl">
          <p className="font-bold mb-2">Sièges sélectionnés:</p>
          <div className="flex flex-wrap gap-2">
            {selectedSeats.map(seat => (
              <span key={seat} className="px-3 py-1 bg-purple-600 text-white rounded-full text-sm font-bold">
                {seat}
              </span>
            ))}
          </div>
        </div>
      )}
    </div>
  );
};

export default SeatSelector;
