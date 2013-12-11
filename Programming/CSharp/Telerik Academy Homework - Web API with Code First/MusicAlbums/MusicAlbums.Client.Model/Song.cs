using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace MusicAlbums.Client.Model
{
    public class Song
    {
        private string title;
        private string genre;


        public int Id { get; set; }

        public string Title
        {
            get
            {
                return this.title;
            }
            set
            {
                if (value.Length > 100)
                {
                    throw new ArgumentOutOfRangeException("The Song Title must be between 1 and 100 characters long.");
                }
                this.title = value;
            }
        }

        public string Genre
        {
            get
            {
                return this.genre;
            }
            set
            {
                if (value.Length > 50)
                {
                    throw new ArgumentOutOfRangeException("The Song Genre must be less than 50 characters long.");
                }
                this.genre = value;
            }
        }

        public int Year { get; set; }

        public virtual Artist Artist { get; set; }

        public virtual ICollection<Album> Albums { get; set; }

        public Song(string title)
        {
            this.Albums = new HashSet<Album>();

            this.title = title;
        }
    }
}
