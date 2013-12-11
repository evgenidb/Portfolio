using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace MusicAlbums.Client.Model
{
    public class Artist
    {
        private string name;
        private string country;


        public int Id { get; set; }
        
        public string Name
        {
            get
            {
                return this.name;
            }
            set
            {
                if (value.Length > 100)
                {
                    throw new ArgumentOutOfRangeException("The Artist Name must be between 1 and 100 characters long.");
                }
                this.name = value;
            }
        }
        
        public string Country {
            get
            {
                return this.country;
            }
            set
            {
                if (value.Length > 50)
                {
                    throw new ArgumentOutOfRangeException("The Artist Country must be less than 50 characters long.");
                }
                this.country = value;
            }
        }

        public DateTime DateOfBirth { get; set; }

        // Navigation Properties
        public virtual ICollection<Song> Songs { get; set; }

        public virtual ICollection<Album> Albums { get; set; }

        public Artist(string name)
        {
            this.Songs = new HashSet<Song>();

            this.Albums = new HashSet<Album>();

            this.Name = name;
        }
    }
}
