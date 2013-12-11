namespace MusicAlbums.Model
{
    using System;
    using System.Collections.Generic;
    using System.ComponentModel.DataAnnotations;

    public class Album
    {
        public int Id { get; set; }

        [Required]
        [StringLength(100,
            MinimumLength = 1,
            ErrorMessage = "The Album Title must be between 1 and 100 characters long.")]
        public string Title { get; set; }

        [StringLength(100,
            ErrorMessage = "The Album Producer must be less than 100 characters long.")]
        public string Producer { get; set; }

        public int Year { get; set; }

        // Navigation Properties
        public virtual ICollection<Song> Songs { get; set; }

        public virtual ICollection<Artist> Artists { get; set; }

        public Album()
        {
            this.Songs = new HashSet<Song>();

            this.Artists = new HashSet<Artist>();
        }
    }
}
