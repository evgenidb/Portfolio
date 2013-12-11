using System;
using System.Collections.Generic;
using System.Data;
using System.Data.Entity;
using System.Data.Entity.Infrastructure;
using System.Linq;
using System.Net;
using System.Net.Http;
using System.Web;
using System.Web.Http;
using MusicAlbums.Model;
using MusicAlbums.Data;
using MusicAlbums.Repositories;

namespace MusicAlbums.WebApi.Controllers
{
    public class ArtistsController : ApiController
    {
        //private MusicAlbumsContext db = new MusicAlbumsContext();
        private readonly IRepository<Artist> artistsRepository;

        public ArtistsController()
        {
            var dbContext = new MusicAlbumsContext();
            this.artistsRepository = new DbArtistsRepository(dbContext);
        }

        public ArtistsController(IRepository<Artist> repository)
        {
            this.artistsRepository = repository;
        }

        // GET api/Artists
        public IQueryable<Artist> GetArtists()
        {
            //return db.Artists.AsEnumerable();

            return this.artistsRepository.GetAll();
        }

        // GET api/Artists/5
        public Artist GetArtist(int id)
        {
            //Artist artist = db.Artists.Find(id);
            //if (artist == null)
            //{
            //    throw new HttpResponseException(Request.CreateResponse(HttpStatusCode.NotFound));
            //}

            //return artist;

            var artist = this.artistsRepository.Get(id);
            if (artist == null)
            {
                throw new HttpResponseException(Request.CreateResponse(HttpStatusCode.NotFound, "No Artist with such Id."));
            }

            return artist;
        }

        // PUT api/Artists/5
        public HttpResponseMessage PutArtist(int id, Artist artist)
        {
            //if (!ModelState.IsValid)
            //{
            //    return Request.CreateErrorResponse(HttpStatusCode.BadRequest, ModelState);
            //}

            //if (id != artist.Id)
            //{
            //    return Request.CreateResponse(HttpStatusCode.BadRequest);
            //}

            //db.Entry(artist).State = EntityState.Modified;

            //try
            //{
            //    db.SaveChanges();
            //}
            //catch (DbUpdateConcurrencyException ex)
            //{
            //    return Request.CreateErrorResponse(HttpStatusCode.NotFound, ex);
            //}

            //return Request.CreateResponse(HttpStatusCode.OK);

            try
            {
                this.artistsRepository.UpdateAndSave(id, artist);
                return Request.CreateResponse(HttpStatusCode.OK);
            }
            catch (ArgumentNullException ex)
            {
                return Request.CreateResponse(HttpStatusCode.BadRequest, ex.Message);
            }
            catch (ArgumentException ex)
            {
                return Request.CreateResponse(HttpStatusCode.BadRequest, ex.Message);
            }
        }

        // POST api/Artists
        public HttpResponseMessage PostArtist(Artist artist)
        {
            //if (ModelState.IsValid)
            //{
            //    db.Artists.Add(artist);
            //    db.SaveChanges();

            //    HttpResponseMessage response = Request.CreateResponse(HttpStatusCode.Created, artist);
            //    response.Headers.Location = new Uri(Url.Link("DefaultApi", new { id = artist.Id }));
            //    return response;
            //}
            //else
            //{
            //    return Request.CreateErrorResponse(HttpStatusCode.BadRequest, ModelState);
            //}

            this.artistsRepository.AddAndSave(artist);

            HttpResponseMessage response = Request.CreateResponse(HttpStatusCode.Created, artist);
            response.Headers.Location = new Uri(Url.Link("DefaultApi", new { id = artist.Id }));
            return response;
        }

        // DELETE api/Artists/5
        public HttpResponseMessage DeleteArtist(int id)
        {
            //Artist artist = db.Artists.Find(id);
            //if (artist == null)
            //{
            //    return Request.CreateResponse(HttpStatusCode.NotFound);
            //}

            //db.Artists.Remove(artist);

            //try
            //{
            //    db.SaveChanges();
            //}
            //catch (DbUpdateConcurrencyException ex)
            //{
            //    return Request.CreateErrorResponse(HttpStatusCode.NotFound, ex);
            //}

            //return Request.CreateResponse(HttpStatusCode.OK, artist);

            try
            {
                var artist = this.artistsRepository.DeleteAndSave(id);
                return Request.CreateResponse(HttpStatusCode.OK, artist);
            }
            catch (NullReferenceException ex)
            {
                return Request.CreateResponse(HttpStatusCode.NotFound, ex.Message);
            }
        }

        protected override void Dispose(bool disposing)
        {
            //db.Dispose();
            this.artistsRepository.Dispose();
            base.Dispose(disposing);
        }
    }
}