namespace MusicAlbums.Model
{
    using System;
    using System.Collections.Generic;
    using System.ComponentModel.DataAnnotations;
    using System.ComponentModel.DataAnnotations.Schema;

    public class Song
    {
        public int Id { get; set; }

        [Required]
        [StringLength(100,
            MinimumLength = 1,
            ErrorMessage = "The Song Title must be between 1 and 100 characters long.")]
        public string Title { get; set; }

        [StringLength(50,
            ErrorMessage = "The Song Genre must be less than 50 characters long.")]
        public string Genre { get; set; }

        public int Year { get; set; }

        // Navigation Properties
        public int ArtistId { get; set; }
        [ForeignKey("ArtistId")]
        public virtual Artist Artist { get; set; }

        public virtual ICollection<Album> Albums { get; set; }

        public Song()
        {
            this.Albums = new HashSet<Album>();
        }
    }
}
