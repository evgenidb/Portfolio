using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace MusicAlbums.Client.Model
{
    public class Album
    {
        private string title;
        private string producer;

        public int Id { get; set; }

        
        public string Title
        {
            get
            {
                return this.title;
            }
            set
            {
                if (string.IsNullOrWhiteSpace(value) || value.Length > 100)
                {
                    throw new ArgumentOutOfRangeException("The Album Title must be between 1 and 100 characters long.");
                }

                this.title = value;
            }
        }

        public string Producer
        {
            get
            {
                return this.producer;
            }
            set
            {
                if (value.Length > 100)
                {
                    throw new ArgumentOutOfRangeException("The Album Producer must be less than 100 characters long.");
                }
                this.producer = value;
            }
        }

        public int Year { get; set; }

        // Navigation Properties
        public virtual ICollection<Song> Songs { get; set; }

        public virtual ICollection<Artist> Artists { get; set; }

        public Album(string title)
        {
            this.Songs = new HashSet<Song>();

            this.Artists = new HashSet<Artist>();

            this.Title = title;
        }
    }
}
