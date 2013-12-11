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
    public class AlbumsController : ApiController
    {
        //private MusicAlbumsContext db = new MusicAlbumsContext();
        private IRepository<Album> albumsRepository;

        public AlbumsController()
        {
            var dbContext = new MusicAlbumsContext();
            this.albumsRepository = new DbAlbumsRepository(dbContext);
        }

        public AlbumsController(IRepository<Album> repository)
        {
            this.albumsRepository = repository;
        }
        

        // GET api/Albums
        public IQueryable<Album> GetAlbums()
        {
            //return db.Albums.AsEnumerable();

            return this.albumsRepository.GetAll();
        }

        // GET api/Albums/5
        public Album GetAlbum(int id)
        {
            //Album album = db.Albums.Find(id);
            //if (album == null)
            //{
            //    throw new HttpResponseException(Request.CreateResponse(HttpStatusCode.NotFound));
            //}

            //return album;

            var album = this.albumsRepository.Get(id);
            if (album == null)
            {
                throw new HttpResponseException(Request.CreateResponse(HttpStatusCode.NotFound, "No Album with such Id."));
            }

            return album;
        }

        // PUT api/Albums/5
        public HttpResponseMessage PutAlbum(int id, Album album)
        {
            //if (!ModelState.IsValid)
            //{
            //    return Request.CreateErrorResponse(HttpStatusCode.BadRequest, ModelState);
            //}

            //if (id != album.Id)
            //{
            //    return Request.CreateResponse(HttpStatusCode.BadRequest);
            //}

            //db.Entry(album).State = EntityState.Modified;

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
                this.albumsRepository.UpdateAndSave(id, album);
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

        // POST api/Albums
        public HttpResponseMessage PostAlbum(Album album)
        {
            //if (ModelState.IsValid)
            //{
            //    db.Albums.Add(album);
            //    db.SaveChanges();

            //    HttpResponseMessage response = Request.CreateResponse(HttpStatusCode.Created, album);
            //    response.Headers.Location = new Uri(Url.Link("DefaultApi", new { id = album.Id }));
            //    return response;
            //}
            //else
            //{
            //    return Request.CreateErrorResponse(HttpStatusCode.BadRequest, ModelState);
            //}

            this.albumsRepository.AddAndSave(album);

            HttpResponseMessage response = Request.CreateResponse(HttpStatusCode.Created, album);
            response.Headers.Location = new Uri(Url.Link("DefaultApi", new { id = album.Id }));
            return response;
        }

        // DELETE api/Albums/5
        public HttpResponseMessage DeleteAlbum(int id)
        {
            //Album album = db.Albums.Find(id);
            //if (album == null)
            //{
            //    return Request.CreateResponse(HttpStatusCode.NotFound);
            //}

            //db.Albums.Remove(album);

            //try
            //{
            //    db.SaveChanges();
            //}
            //catch (DbUpdateConcurrencyException ex)
            //{
            //    return Request.CreateErrorResponse(HttpStatusCode.NotFound, ex);
            //}

            //return Request.CreateResponse(HttpStatusCode.OK, album);

            try
            {
                var album = this.albumsRepository.DeleteAndSave(id);
                return Request.CreateResponse(HttpStatusCode.OK, album);
            }
            catch (NullReferenceException ex)
            {
                return Request.CreateResponse(HttpStatusCode.NotFound, ex.Message);
            }
        }

        protected override void Dispose(bool disposing)
        {
            //db.Dispose();
            this.albumsRepository.Dispose();
            base.Dispose(disposing);
        }
    }
}